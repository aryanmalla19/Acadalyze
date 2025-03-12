<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class SchoolController extends Controller
{
    private $schoolModel;

    public function __construct()
    {
        $this->schoolModel = $this->model('School');
    }

    public function show(Request $request, $id): void
    {
        $school = $this->schoolModel->findById($id);
        if($school){
            $this->sendResponse("success", "School data fetched successfully", $school);
        }
        $this->sendResponse("error", "School with ID $id does not exists", null, 404);
    }
    
    public function index(Request $request): void
    {
        $school_id = $request->getUser()->school_id;
        if(!$school_id){
        $this->sendResponse("error", "You are not associated with any school", null, 404);
        }
        $this->show($request, $school_id);
    }


    public function create(Request $request): void
    {
        $data = $request->body + [
            'school_email' => '',
            'school_name' => '',
            'established_date' => '',
            'telephone_number' => '',
            'address' => '',
        ];

        // Define validation rules
        $rules = [
            'school_email' => 'required|email',
            'school_name' => 'required|min:3|max:50',
            'established_date' => 'required|min:6|max:20',
            'telephone_number' => 'required|min:2|max:30',
            'address' => 'required|min:2|max:30',
        ];
        if (!$this->schoolModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->schoolModel->getErrors(), [], 400);
        }

        $userModel = $this->model('User');
        if($userModel->getSchoolByUserId($request->user['user_id'])){
            $this->sendResponse("error", "You are already associated with one school", [], 409);
        }

        // Check if email is already registered
        if ($this->schoolModel->getByEmail($data['school_email'])) {
            $this->sendResponse("error", "School Email (". $data['school_email'] .") is already registered", [], 409);
        }

        try {
            // Create the new School
            $newSchoolID = $this->schoolModel->create($data['school_name'], $data['school_email'], $data['established_date'], $data['telephone_number'], $data['address']);
            if($newSchoolID){
                $userModel->setSchoolId($request->user['user_id'], $newSchoolID);
            }
            $this->sendResponse("success", "Registration successful with School ID $newSchoolID", null);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        $userModel = $this->model('User');
        $userCount = $userModel->countUsersBySchoolId($id);
        
        if ($userCount > 0) {
            $this->sendResponse("error", "Cannot delete school ID $id. There are users still associated with this school.", null, 400);
            return;
        }

        $isDeleted = $this->schoolModel->deleteById($id);
        if ($isDeleted) {
            $this->sendResponse("success", "Successfully deleted School ID $id", null);
        }
        $this->sendResponse("error", "Not Found. Could not delete School ID $id", null, 404);
    }

    
    public function update(Request $request, $id): void
    {
        $data = $request->body;

        $allRules = [
            'school_email' => 'email',
            'school_name' => 'min:2|max:50',
            'address' => 'min:2|max:30',
            'established_date' => 'min:2|max:30',
            'telephone_number' => 'min:3|max:20',
        ];

        if(!empty(array_diff_key($data, $allRules))){
            $this->sendResponse("error", "Incorrect params for school update", array_diff_key($data, $allRules), 400);
        }

        $rules = array_intersect_key($allRules, $data);
        
        $errors = $this->schoolModel->validate($data, $rules);
        if (!($errors)) {
            $this->sendResponse("error", "Validation failed", $this->schoolModel->getErrors(), 400);
        }

        if(!empty($data['school_email'])){
            $existedSchool =  $this->schoolModel->getByEmail($data['school_email']);
            if($existedSchool){
                $this->sendResponse("error", "School Email already exists", null, 409);
            }
        }

        try {
            if($this->schoolModel->updateById($id, $data)){
                $this->sendResponse("success", "School updated successfully", $data);
            }
            $this->sendResponse("error", "No data provided to update school", $data, 400);
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

}