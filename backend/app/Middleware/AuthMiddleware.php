<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Auth;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, callable $next, ...$args): array
    {
        // Fetch the token from cookies instead of headers
        $token = $request->getCookie('token', ''); 
        
        if ($token) {
            $userData = Auth::validateToken($token);
            if ($userData) {
                $request->user = $userData;
                return $this->proceed($request, $next);
            }
        }

        return $this->sendResponse("error", "Unauthorized! Token is expired or invalid. Please log in again.", [], 401);
    }
}
