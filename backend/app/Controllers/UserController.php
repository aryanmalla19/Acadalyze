<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function getAllUsers()
    {
        $users = $this->userModel->getAllUsers();
        $this->sendResponse("success", "All Users data fetched successfully", $users);
    }

    public function getUserById($requestData, $id)
    {
        $user = $this->userModel->getUserById($id);

        if ($user) {
            $this->sendResponse("success", "User data fetched successfully", $user);
        } else {
            $this->sendResponse("error", "User with ID $id does not exist", [], 404);
        }
    }

    public function updateUser($requestData, $id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        // Update logic here (e.g., $this->userModel->updateUser($id, $data))
        $this->sendResponse("success", "User updated successfully", ["id" => $id, "data" => $data]);
    }

    public function deleteUser($requestData, $id)
    {
        if($this->userModel->deleteUser($id)){
            $this->sendResponse("success", "User deleted successfully", $id);
        }
        $this->sendResponse("error", "Could not find User with ID $id", false);

    }
}
