<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Models\User;

class RoleMiddleware extends Middleware
{
    public function handle(Request $request, callable $next, ...$args): array
    {
        $requiredRoles = $args[0] ?? [];
        $user = User::find($request->user['user_id']);
        $userRole = $user->role_name ?? 'Guest';
        if ($userRole && in_array($userRole, $requiredRoles, true)) {
            return $this->proceed($request, $next);
        }
        $this->sendResponse("error", "Forbidden: Insufficient role permissions", [], 403);
    }
}   