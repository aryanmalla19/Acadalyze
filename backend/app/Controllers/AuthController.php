<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Controller;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request): void
    {
        $data = $request->body;

        if (empty($data['identifier'])) {
            $this->sendResponse("error", "Email or username is required", [], 400);
        }
        if (empty($data['password'])) {
            $this->sendResponse("error", "Password is required", [], 400);
        }

        $user = new User();
        $userData = $user->getByIdentifier($data['identifier']);

        if (!$userData) {
            $this->sendResponse("error", "Email or username does not exist", null, 404);
        }
        
        if (!password_verify($data['password'], $userData['password'])) {
            $this->sendResponse("error", "Invalid credentials", [], 401);
        }

        try {
            $token = Auth::generateToken($userData['role_name'], $userData['user_id'], $data['identifier']);
            
            // Set the token as a cookie with the httpOnly flag to prevent JS access
            setcookie('token', $token, time() + JWT_EXPIRATION, '/', '', false, true);
            
            $this->sendResponse("success", "Login successful", ['email'=>$userData['email'], 'username'=> $userData['username'], 'role'=>$userData['role_name']]);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    public function register(Request $request): void
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
            'role' => '',
        ];

        // Define validation rules
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6|max:30',
            'username' => 'required|min:3|max:20',
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'address' => 'required|min:3|max:20',
            'phone_number' => 'required|min:5|max:10',
            'date_of_birth' => 'required',
            'role' => 'required',
        ];


        if(!empty($data['school_id'])){
            $token = $request->getCookie('token', '');     
            if ($token) {
                $userData = Auth::validateToken($token);
                if (!isset($userData) || !$userData) {
                $this->sendResponse("error", "You must be logged in to register other users", [], 403);
                }
            }
            
            $currentUser = User::find($userData['user_id']);
            if ($currentUser['role_name'] != 'Admin' && $currentUser['role_name'] != 'Teacher') {
                $this->sendResponse("error", "You must be an Admin or Teacher", [], 403);
            }

            if($currentUser['role_name'] == 'Teacher' ){
                if($data['role'] == 'Teacher' || $data['role'] == 'Admin'){
                    $this->sendResponse("error", "Cannot register Teacher or Admin when you are Teacher", [], 403);
                }
            }
            if($currentUser->school_id != $data['school_id']){
                $this->sendResponse("error", "Cannot register user of another school", [], 400);
            }
        }

        // Validate the data
        $user = new User();
        
        if (!$user->validate($data, $rules)) {
            $this->sendResponse("error", $user->getErrors(), [], 400);
        }
        
        $role = new Role();
        $userRole = $role->getIdByRole($data['role']);

        if(empty($userRole)){
            $this->sendResponse("error", "Inavlid role. Role must be Admin | Student | Teacher | Parents", [], 400);
        }

        try {
            // Check if email is already registered
            if ($user->getByEmail($data['email'])) {
                $this->sendResponse("error", "Email is already registered", [], 409);
            }

            // Check if username is already registered
            if ($user->getByUsername($data['username'])) {
                $this->sendResponse("error", "Username is already registered", [], 409);
            }
            
            // Hash the password before passing it to the model
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Create the new user
            $newUser = $user->create([
                'email' => $data['email'],
                'password' => $hashedPassword,
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'parent_phone_number' => $data['parent_phone_number'],
                'role_id' => $userRole['role_id'],
                'school_id' => $userRole['school_id'],
            ]);
            $this->sendResponse("success", "Registration successful", ['user_id'=>$newUser['user_id'], 'email'=>$data['email'], 'username'=>$data['username'], 'role'=>$data['role']]);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    public function logout(Request $request): void
    {
        setcookie('token', '', time() - 3600, '/', '', false, true);
        $this->sendResponse("success", "Logged out successfully");
    }

    public function verify(Request $request)
    {
        $token = $request->getCookie('token', ''); 

        if ($token) {
            $userData = Auth::validateToken($token);
            if ($userData) {
                $this->sendResponse("success", "Successfully verified! Authentication completed", $userData);
            }
        }
        $this->sendResponse("error", "Unauthorized! Token is expired or invalid. Please log in again.", null, 401);
    }
}