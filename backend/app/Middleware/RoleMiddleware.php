<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;

class RoleMiddleware extends Middleware
{
    public function handle(Request $request, callable $next, ...$args): array
    {
        $requiredRoles = $args[0] ?? [];
        $userRole = $request->user['role'] ?? 'Guest';
        if ($userRole && in_array($userRole, $requiredRoles, true)) {
            return $this->proceed($request, $next);
        }
        $this->sendResponse("error", "Forbidden: Insufficient role permissions", [], 403);
    }
}   