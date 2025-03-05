<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $userModel   = $this->model('User');
        $users = $userModel->getAllUsers();
        echo json_encode([
            "status" => "success",
            "message" => "All Users data fetched sucessfully",
            "data" => $users,
        ]);
    }

    public function getUserById($data)
    {
        $id = $data['id'];
        $userModel = $this->model('User');
        $user = $userModel->getUserById($id);
        if($user){
            echo json_encode([
                "status" => "success",
                "message" => "User data fetched sucessfully",
                "data" => $user,
            ]);
            return;
        }
        echo json_encode([
            "status" => "error",
            "message" => "Could not find User with  ID"
        ]);
    }

    public function updateUser($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(["message" => "User updated", "id" => $id, "data" => $data]);
    }

    public function deleteUser($id)
    {
        echo json_encode(["message" => "User deleted", "id" => $id]);
    }
}
