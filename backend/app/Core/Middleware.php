<?php
namespace App\Core;

abstract class Middleware
{
    public function __construct()
    {
    }

    abstract public function handle(Request $request, callable $next, ...$args): array;

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
}