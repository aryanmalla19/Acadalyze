<?php
namespace App\Core;

abstract class Middleware
{

    abstract public function handle(Request $request, callable $next, ...$args);

    protected function proceed(Request $request, callable $next): array
    {
        return $next($request);
    }

    protected function sendResponse($status, $message, $data = [], $code = 200)
    {
        http_response_code($code);
        if(empty($data)){
            echo json_encode([
                "status" => $status,
                "message" => $message,
            ]);
            exit();
        }
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data
        ]);
        exit();
    }

    protected function sendError(string $message, int $code): never
    {
        http_response_code($code);
        echo json_encode(['status'=>'error', 'message' => $message]);
        exit;
    }
}