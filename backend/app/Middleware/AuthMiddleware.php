<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Auth;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, callable $next, ...$args): array
    {
        $authHeader = $request->getHeader('Authorization', '');
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            $userData = Auth::validateToken($token);
            if ($userData) {
                $request->user = $userData;
                return $this->proceed($request, $next);
            }
        }
        $this->sendResponse("error", "Unauthorized", [], 401);
    }
}