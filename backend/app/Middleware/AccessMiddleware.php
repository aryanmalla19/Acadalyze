<?php
namespace App\Middleware;

use App\Core\Request;

class AccessMiddleware
{
    public function handle(Request $request, callable $next, string $policyClass): mixed
    {
        $authUser = $request->user;
        if(!$authUser){
            http_response_code(404);
            echo json_encode(['status' => 'error', 'error'=> 'Authentication not found' ]);
            exit();
        }

        $modelId = $request->getParam('id');
        if(!$modelId){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error'=> 'Missing model ID']);
            exit();
        }
        
        // Resolve model class based on route URI (simplified example)
        $modelClass = $this->resolveModelClass($request);
        
        $model = $modelClass::find($modelId);
        if(!$model){
            http_response_code(404);
            echo json_encode(['status' => 'error', 'error'=> 'Model not found']);
            exit();
        }
        $authUser = $modelClass::find($request->user['user_id']);
        if (!class_exists($policyClass)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'error'=> 'Policy class does not exist' ]);
            exit();
        }
        
        $policyClass = "\\" . $policyClass;
        $policy = new  $policyClass();
        if (!method_exists($policy, 'view')) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'error'=> "Policy class must implement view method: $policyClass" ]);
            exit();
        }
        
        if (!$policy->view($authUser, $model)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'error'=> 'You are not authorized to access this data' ]);
            exit();
        }
        return $next($request);
    }

    private function resolveModelClass(Request $request): string
    {
        $uri = $request->uri;
        if (str_starts_with($uri, '/api/users')) {
            return \App\Models\User::class;
        }
        if (str_starts_with($uri, '/api/exams')) {
            return \App\Models\Exam::class;
        }
        
        http_response_code(500);
        echo json_encode(['status' => 'error', 'error'=> 'Unable to resolve model class for route' ]);
        exit();
    }
}