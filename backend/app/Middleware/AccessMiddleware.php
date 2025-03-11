<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Middleware;

class AccessMiddleware extends Middleware
{
    public function handle(Request $request, callable $next, ...$args)
    {
        try {
            // Check for policy class
            $policyClass = $args[0]['policy'] ?? $this->sendError("Policy class not specified", 500);
            $action = $args[0]['action'] ?? 'view';
            $modelClass = $args[0]['modelClass'] ?? null;
            $className = basename(str_replace("\\", "/", $modelClass));

            // Check for authenticated user
            $authUser = $request->getUser() ?? $this->sendError("Unauthorized", 401);

            $model = null;
            if (in_array($action, ['view', 'update', 'delete'])) {
                if (!$modelClass) {
                    return $this->sendError("$className class required for $action", 500);
                }
                $modelId = $request->getParam('id');
                $model = $modelClass::find($modelId) ?? $this->sendError("$className with ID $modelId not found", 404);
            }

            // Instantiate policy and validate action method
            $policy = new $policyClass();
            if (!method_exists($policy, $action)) {
                return $this->sendError("Policy missing $action method", 500);
            }

            // Use reflection to check policy method parameters and invoke
            $method = new \ReflectionMethod($policy, $action);
            $params = $method->getParameters();

            if ($model && count($params) == 2) {
                if (!$method->invoke($policy, $authUser, $model)) {
                    return $this->sendError("Forbidden", 403);
                }
            } elseif (!$model && count($params) == 1) {
                if (!$method->invoke($policy, $authUser)) {
                    return $this->sendError("Forbidden", 403);
                }
            } else {
                return $this->sendError("Policy method parameter mismatch", 500);
            }

            // Proceed to next middleware if all checks pass
            return $this->proceed($request, $next);
        } catch (\ReflectionException $e) {
            return $this->sendError("Reflection error: " . $e->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->sendError("Internal Server Error: " . $e->getMessage(), 500);
        }
    }

}