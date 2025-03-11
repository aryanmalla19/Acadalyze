<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index(Request $request): void
    {
        $school_id = $request->user['school_id'];

        if(!$school_id){
            $this->sendResponse("error", "You are not asssociated with any school", $users);
        }
        
        $users = $this->userModel->getAllUsersBySchoolId($school_id);
        
        if(!empty($users)){
            $this->sendResponse("success", "All Users data fetched successfully", $users);
        }

        $this->sendResponse("error", "No Users data found", null, 404);
    }

    public function show(Request $request, string $id): void 
    {
        $user = $this->userModel->getUserById($id);
        if ($user) {
            $this->sendResponse("success", "User data fetched successfully", $user);
        } else {
            $this->sendResponse("error", "User with ID $id does not exist", [], 404);
        }
    }

    public function update(Request $request, string $id): void
    {
        $data = $request->body;

        // Define validation rules for updatable fields
        $allRules = [
            'email' => 'email',
            'username' => 'min:6|max:20',
            'first_name' => 'min:2|max:30',
            'last_name' => 'min:2|max:30',
            'address' => 'min:3|max:20',
            'phone_number' => 'min:6|max:10',
            'parent_phone_number' => 'min:6|max:10',
            'date_of_birth' => 'date:Y-m-d',
        ];

        // Filter rules to only include those for fields present in $data
        $rules = array_intersect_key($allRules, $data);

        // Validate the data (only for provided fields)
        $errors = $this->userModel->validate($data, $rules);
        if (!($errors)) {
            $this->sendResponse("error", "Validation failed", $this->userModel->getErrors(), 400);
            return;
        }

        if(!empty($data['email'])){
            $existedUser =  $this->userModel->getUserByEmail($data['email']);
            if($existedUser){
            $this->sendResponse("error", "Email already exists", null, 409);
            }
        }

        if(!empty($data['username'])){
            $existedUser =  $this->userModel->getUserByUsername($data['username']);
            if($existedUser){
                $this->sendResponse("error", "Username already exists", null, 409);
                }
        }

        try {
            $this->userModel->updateUser($id, $data);
            $this->sendResponse("success", "User updated successfully", $data);
        } catch (\InvalidArgumentException $e) {
            $this->sendResponse("error", $e->getMessage(), [], 400);
        } catch (\PDOException $e) {
            // Handle SQL-specific errors like duplicates
            if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $field = $this->extractDuplicateField($e->getMessage());
                $this->sendResponse(
                    "error",
                    "Duplicate value detected",
                    ['field' => $field, 'value' => $data[$field] ?? 'unknown'],
                    409 // Conflict status code
                );
            } else {
                $this->sendResponse("error", "Database error occurred", ['details' => $e->getMessage()], 500);
            }
        } catch (\RuntimeException $e) {
            $this->sendResponse("error", $e->getMessage(), [], 500);
        }
    }

    public function destroy(string $id): void
    {
        if ($this->userModel->deleteUser($id)) {
            $this->sendResponse("success", "User deleted successfully", $id);
        }
        $this->sendResponse("error", "Could not find User with ID $id", false);
    }
}