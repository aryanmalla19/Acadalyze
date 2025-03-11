<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Controller;
use Exception;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->body;
        if (empty($data['identifier'])) {
            $this->sendResponse("error", "Email or username is required", [], 400);
        }
        if (empty($data['password'])) {
            $this->sendResponse("error", "Password is required", [], 400);
        }

        $user = new User();
    
        // Check credentials using the identifier (email or username)
        $userData = $user->getUserByIdentifier($data['identifier']);

        if(!$userData){
            $this->sendResponse("error", "Email or username does not exists", null, 404);
        }
        
        if (!$userData || !password_verify($data['password'], $userData['password'])) {
            $this->sendResponse("error", "Invalid credentials", [], 401);
        }

        try {
            $token = Auth::generateToken($userData['role_name'], $userData['user_id'], $data['identifier']);
            $this->sendResponse("success", "Login successful", ["token" => $token]);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    public function register(Request $request)
    {
        $data = $request->body + [
            'email' => '',
            'password' => '',
            'username' => '',
            'first_name' => '',
            'last_name' => '',
            'address' => '',
            'phone_number' => '',
            'parent_phone_number' => '',
            'date_of_birth' => '',
        ];

        // Define validation rules
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6|max:30',
            'username' => 'required|min:6|max:20',
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'address' => 'required|min:3|max:20',
            'phone_number' => 'required|min:6|max:10',
            'date_of_birth' => 'required',
        ];

        // Validate the data
        $user = new User();
        if (!$user->validate($data, $rules)) {
            $this->sendResponse("error", $user->getErrors(), [], 400);
        }

        // Hash the password before passing it to the model
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        try {
            // Check if email is already registered
            if ($user->getUserByEmail($data['email'])) {
                $this->sendResponse("error", "Email is already registered", [], 409);
            }

            // Check if username is already registered
            if ($user->getUserByUsername($data['username'])) {
                $this->sendResponse("error", "Username is already registered", [], 409);
            }

            // Create the new user
            $newUser = $user->createUser([
                'email' => $data['email'],
                'password' => $hashedPassword,
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'parent_phone_number' => $data['parent_phone_number'],
            ]);

            $this->sendResponse("success", "Registration successful", $newUser);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

}
