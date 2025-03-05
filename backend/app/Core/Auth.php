<?php
namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Auth {
    public static function generateToken($userId, $identifier) {
        $payload = [
            "iat" => time(), // Issued at
            "exp" => time() + JWT_EXPIRATION, // Expiry time
            "sub" => $userId, // User ID
            "identifier" => $identifier // User Email
        ];

        return JWT::encode($payload, JWT_SECRET, JWT_ALGO);
    }


    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
            return (array) $decoded;
        } catch (Exception $e) {
            return false; // Invalid token
        }
    }
}