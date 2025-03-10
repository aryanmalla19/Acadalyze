<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class RoleController extends Controller
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = $this->model('Role');
    }
    
    // Show a specific role by ID
    public function show(Request $request, $id)
    {
        $role = $this->roleModel->read($id); // Fetch role by ID
        if (!$role) {
            $this->sendResponse("error", "Role with ID $id not found", null, 404); 
            return; 
        }
        $this->sendResponse("success", "Role data fetched successfully", $role);
    }

    public function create(Request $request)
    {
        $body = $request->body;
        if (empty($body['name'])) {
            $this->sendResponse("error", "Role name is required", null, 400);
            return;
        }

        $existingRole = $this->roleModel->readByName($body['name']);
        if ($existingRole) {
            $this->sendResponse("error", "Role with name {$body['name']} already exists", null, 409); // 409 Conflict
            return;
        }

        // Create the new role
        $newRole = $this->roleModel->create($body['role_name']); // Assuming $body contains necessary role data
        $this->sendResponse("success", "Role created successfully", $newRole, 201); // 201 Created
    }
}
