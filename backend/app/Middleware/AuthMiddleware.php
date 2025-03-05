<?php
namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware {
    public static function handle() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (!$token) {
            http_response_code(401);
            echo json_encode(["status" => "error","message" => "Unauthorized: Token required"]);
            exit;
        }

        $decoded = Auth::validateToken(str_replace('Bearer ', '', $token));

        if (!$decoded) {
            http_response_code(403);
            echo json_encode(["status" => "error","message" => "Forbidden: Invalid or expired token"]);
            exit;
        }

        return $decoded;
    }
}
