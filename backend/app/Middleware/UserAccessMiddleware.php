<?php
namespace App\Middleware;

use App\Middleware\AuthMiddleware;

class UserAccessMiddleware {
    public static function handle($params = []) 
    {
        $user = AuthMiddleware::handle(); // Authenticate the user first
        // Extract `id` from route parameters
        $requestedUserId = $params['id'] ?? $_GET['id'] ?? null;
        
        if($user['role'] == 'Admin'){
            return $user; 
        }
        
        if ($user['user_id'] != $requestedUserId) {
            http_response_code(403);
            echo json_encode(["status" => "error","message" => "Forbidden: You can only access your own data"]);
            exit;
        }

        return $user; // Proceed with the request
    }
}
