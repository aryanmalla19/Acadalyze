<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, $handler, ?array $middlewares = [])
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException("Handler must be callable");
        }

        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);

        $this->routes[] = [
            "method" => strtoupper($method),
            "path" => "#^" . $path . "$#",
            "handler" => $handler,
            "middlewares" => $middlewares
        ];
    }

    public function route()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        header("Content-Type: application/json");

        foreach ($this->routes as $route) {
            if ($route["method"] === $requestMethod && preg_match($route["path"], $requestUri, $matches)) {
                $params = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                foreach ($route["middlewares"] as $middleware) {
                    if (is_array($middleware)) {
                        [$middlewareClass, $middlewareArgs] = array_pad((array)$middleware, 2, null);
                        if (class_exists($middlewareClass) && method_exists($middlewareClass, "handle")) {
                            try {
                                if ($middlewareArgs !== null) {
                                    $middlewareClass::handle($middlewareArgs);
                                } else {
                                    $middlewareClass::handle();
                                }
                            } catch (\Throwable $e) {
                                http_response_code(403);
                                echo json_encode(["error" => "Forbidden", "message" => $e->getMessage()]);
                                return;
                            }
                        }
                    } elseif (class_exists($middleware) && method_exists($middleware, "handle")) {
                        try {
                            $middleware::handle();
                        } catch (\Throwable $e) {
                            http_response_code(403);
                            echo json_encode(["error" => "Forbidden", "message" => $e->getMessage()]);
                            return;
                        }
                    }
                }

                try {
                    call_user_func_array($route["handler"], $params);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    echo json_encode(["error" => "Internal Server Error", "message" => $e->getMessage()]);
                }
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Not Found", "message" => "Route not found"]);
    }
}