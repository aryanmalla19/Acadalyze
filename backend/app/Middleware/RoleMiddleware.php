<?php
namespace App\Middleware;

use App\Core\Auth;

class RoleMiddleware {
    public static function handle(array $allowedRoles) {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (!$token) {
            throw new \Exception("Unauthorized: Token required");
        }

        $userData = Auth::validateToken(str_replace('Bearer ', '', $token));

        if (!$userData) {
            throw new \Exception("Forbidden: Invalid or expired token");
        }

        // Check if user role is in allowed roles
        if (!in_array($userData['role'], $allowedRoles)) {
            throw new \Exception("Forbidden: You do not have permission to access this resource.");
        }

        return $userData; // Allow request to proceed
    }
}
