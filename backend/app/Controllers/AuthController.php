<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;
use App\Core\Controller;
use Exception;

class AuthController extends Controller
{public function login()
    {
        // Get login data from a single identifier field
        $data = [
            'identifier' => $_POST['identifier'] ?? '', // Single field for email or username
            'password' => $_POST['password'] ?? ''
        ];
    
        // Validate input
        if (empty($data['identifier'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "error" => "Email or username is required"]);
            return;
        }
        if (empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "error" => "Password is required"]);
            return;
        }
    
        $user = new User();
    
        // Check credentials using the identifier (email or username)
        $userData = $user->getUserByIdentifier($data['identifier']);
    
        if (!$userData || !password_verify($data['password'], $userData['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid credentials"]);
            return;
        }
    
        // Generate JWT token
        try {
            $token = Auth::generateToken($userData['user_id'], $data['identifier']); // This will create the JWT token
            echo json_encode([
                "message" => "Login successful",
                "token" => $token
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error", "message" => $e->getMessage()]);
        }
    }

    public function register()
    {
        // Get registration data
        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'username' => $_POST['username'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone_number' => $_POST['phone_number'] ?? '',
            'parent_phone_number' => $_POST['parent_phone_number'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? '',
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
            // If validation fails, return errors
            http_response_code(400);
            echo json_encode(["errors" => $user->getErrors()]);
            return;
        }

        // Hash the password before passing it to the model
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        try {
            // Check if email is already registered
            if ($user->getUserByEmail($data['email'])) {
                http_response_code(409);
                echo json_encode(["status"=> "error", "error" => "Email is already registered"]);
                return;
            }

            // Check if username is already registered
            if ($user->getUserByUsername($data['username'])) {
                http_response_code(409);
                echo json_encode(["status"=> "error", "error" => "Username is already registered"]);
                return;
            }

            // Create the new user
            $newUser = $user->createUser([
                'email' => $data['email'],
                'password' => $hashedPassword, // Store the hashed password
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'parent_phone_number' => $data['parent_phone_number'],
            ]);

            echo json_encode([
                "message" => "Registration successful",
                "user" => $newUser
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error", "message" => $e->getMessage()]);
        }
    }

    /**
     * User logout (invalidate token)
     */
    public function logout()
    {
        // Invalidate token (optional, based on your JWT setup)
        // JWT typically doesn't require a logout endpoint since it's stateless
        // So we could just delete the token from client-side storage

        echo json_encode(["message" => "Logout successful"]);
    }
}
