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

        // Validate required fields
        if (empty($data['identifier'])) {
            $this->sendResponse('error', 'Email or username is required', [], 400);
            return;
        }

        if (empty($data['password'])) {
            $this->sendResponse('error', 'Password is required', [], 400);
            return;
        }

        $user = new User();
        $userData = $user->getByIdentifier($data['identifier']);

        // Check credentials (combined for security to avoid user enumeration)
        if (!$userData || !password_verify($data['password'], $userData['password'])) {
            $this->sendResponse('error', 'Invalid credentials', null, 401);
            return;
        }

        try {
            $token = Auth::generateToken($userData['role_name'], $userData['user_id'], $data['identifier']);
            setcookie('token', $token, time() + JWT_EXPIRATION, '/', '', false, true);

            $this->sendResponse('success', 'Login successful', [
                'email' => $userData['email'],
                'username' => $userData['username'],
                'role' => $userData['role_name']
            ]);
        } catch (Exception $e) {
            $this->sendResponse('error', 'Internal Server Error', [], 500);
        }
    }

    public function register(Request $request): void
    {
        // Prepare data by merging request body with defaults
        $data = $this->prepareRegistrationData($request);
        
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

        // Validate input data
        $user = new User();
        if (!$user->validate($data, $rules)) {
            $this->sendResponse('error', $user->getErrors(), [], 400);
            return;
        }
        
        // Handle registration logic
        $this->handleRegistration($request, $data);
    }
    
    private function prepareRegistrationData(Request $request): array
    {
        return $request->body + [
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
            'school_id' => '',
        ];
    }

    private function handleRegistration(Request $request, array $data): void
    {
        $role = new Role();
        $userRole = $role->getIdByRole($data['role']);
        
        if (empty($userRole)) {
            $this->sendResponse('error', 'Invalid role. Role must be Admin, Student, Teacher, or Parent', [], 400);
            return;
        }
        
        if ($data['role'] === 'Admin') {
            // Admins can register without JWT
            $this->createUser($data, $userRole['role_id']);
        } else {
            // Non-Admins require JWT and permission checks
            $this->registerNonAdmin($request, $data, $userRole['role_id']);
        }
    }

    private function registerNonAdmin(Request $request, array $data, int $roleId): void
    {
        $token = $request->getCookie('token', '');
        if (empty($token)) {
            $this->sendResponse('error', 'JWT token is required for registering non-Admin users', [], 403);
            return;
        }

        $userData = Auth::validateToken($token);

        if (!$userData) {
            $this->sendResponse('error', 'Invalid token', [], 401);
            return;
        }
        
        $currentUser = User::find($userData['user_id']);
        if (!$currentUser) {
            $this->sendResponse('error', 'User not found', [], 404);
            return;
        }
        
        // Permission checks
        if ($currentUser->role_name !== 'Admin' && $currentUser->role_name !== 'Teacher') {
            $this->sendResponse('error', 'Only Admins and Teachers can register non-Admin users', [], 403);
            return;
        }
        
        if ($currentUser->role_name === 'Teacher') {
            if (in_array($data['role'], ['Admin', 'Teacher'])) {
                $this->sendResponse('error', 'Teachers cannot register Admins or other Teachers', [], 403);
                return;
            }
            if (empty($data['school_id']) || $currentUser->school_id !== $data['school_id']) {
                $this->sendResponse('error', 'Teachers can only register users from their own school', [], 403);
                return;
            }
        }
        
        if($currentUser->school_id !== $data['school_id']){
            $this->sendResponse('error', 'Can only register users from their own school', [], 403);
            return;
        }
        
        // Create the user
        $this->createUser($data, $roleId);
    }

    private function createUser(array $data, int $roleId): void
    {
        $user = new User();
        // Check for duplicates
        if ($user->getByEmail($data['email'])) {
            $this->sendResponse('error', 'Email is already registered', [], 409);
            return;
        }
        if ($user->getByUsername($data['username'])) {
            $this->sendResponse('error', 'Username is already registered', [], 409);
            return;
        }

        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
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
                'role_id' => $roleId,
                'school_id' => $data['school_id'] ?? null,
            ]);

            $this->sendResponse('success', 'Registration successful', [
                'user_id' => $newUser['user_id'],
                'email' => $data['email'],
                'username' => $data['username'],
                'role' => $data['role']
            ]);
        } catch (Exception $e) {
            $this->sendResponse('error', 'Internal Server Error', [], 500);
        }
    }

    public function logout(Request $request): void
    {
        setcookie('token', '', time() - 3600, '/', '', false, true);
        $this->sendResponse('success', 'Logged out successfully');
    }

    public function verify(Request $request): void
    {
        $token = $request->getCookie('token', '');
        if (empty($token)) {
            $this->sendResponse('error', 'No token provided. Please log in.', null, 401);
            return;
        }

        $userData = Auth::validateToken($token);
        if ($userData) {
            $this->sendResponse('success', 'Token is valid', $userData);
        } else {
            $this->sendResponse('error', 'Token is expired or invalid. Please log in again.', null, 401);
        }
    }
}