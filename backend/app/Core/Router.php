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

        $requestData = $this->getRequestData();

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
                    call_user_func_array($route["handler"], [$requestData, ...array_values($params)]);
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

    private function getRequestData(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'] ?? '';

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $rawInput = file_get_contents('php://input');

            // Handle application/json
            if (stripos($contentType, 'application/json') !== false) {
                $data = json_decode($rawInput, true);
                return (json_last_error() === JSON_ERROR_NONE && is_array($data)) ? $data : [];
            }

            // Handle application/x-www-form-urlencoded
            if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                return $_POST;
            }

            // Handle multipart/form-data
            if (stripos($contentType, 'multipart/form-data') !== false) {
                $data = $_POST; // Regular fields
                if (!empty($_FILES)) {
                    $data['files'] = $_FILES; // Add file data under 'files' key
                }
                return $data;
            }

            // No Content-Type or unrecognized
            if (empty($contentType) || !in_array(strtolower(trim($contentType)), ['application/json', 'application/x-www-form-urlencoded', 'multipart/form-data'])) {
                if (!empty($rawInput)) {
                    $data = json_decode($rawInput, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                        return $data;
                    }
                }
                // Fallback to $_POST for form data without explicit Content-Type
                return $_POST;
            }

            // Unsupported Content-Type with data
            if (!empty($rawInput)) {
                http_response_code(415);
                echo json_encode(["error" => "Unsupported Media Type", "message" => "Only application/json, application/x-www-form-urlencoded, and multipart/form-data are supported"]);
                exit;
            }
        }

        return [];
    }
}