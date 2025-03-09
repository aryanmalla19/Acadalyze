<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path,  $handler, array $middlewares = []): void
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

    public function route(): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? '';
        $requestUri = parse_url($_SERVER["REQUEST_URI"] ?? '', PHP_URL_PATH) ?? '/';
        $headers = getallheaders();
        $query = $_GET ?? [];
        $body = $this->getRequestData();
        $request = new Request($requestMethod, $requestUri, $headers, $query, $body);
        
        foreach ($this->routes as $route) {
            if ($route["method"] === $requestMethod && preg_match($route["path"], $requestUri, $matches)) {
                // var_dump($route['middlewares']);

                $params = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
                $request->params = $params;
                
                $finalHandler = function (Request $req) use ($route, $params) {
                    // Use reflection to get handler parameter names
                    $reflection = is_array($route["handler"])
                    ? new \ReflectionMethod($route["handler"][0], $route["handler"][1])
                    : new \ReflectionFunction($route["handler"]);
                    $handlerParams = [];
                    foreach ($reflection->getParameters() as $param) {
                        $name = $param->getName();
                        if ($name === 'request') {
                            $handlerParams[] = $req; // Pass Request object if 'request' is a param
                        } elseif (isset($params[$name])) {
                            $handlerParams[] = $params[$name]; // Pass route param if name matches
                        } elseif ($param->isDefaultValueAvailable()) {
                            $handlerParams[] = $param->getDefaultValue(); // Use default if available
                        } else {
                            throw new \RuntimeException("Missing required parameter: $name");
                        }
                    }
                    return call_user_func_array($route["handler"], $handlerParams);
                };

                $handler = array_reduce(
                    array_reverse($route["middlewares"]),
                    function ($next, $middlewareDef) {
                        return function (Request $req) use ($middlewareDef, $next) {
                            [$class, $args] = is_array($middlewareDef) ? array_pad($middlewareDef, 2, []) : [$middlewareDef, []];
                            if (!class_exists($class) || !method_exists($class, 'handle')) {
                                throw new \RuntimeException("Invalid middleware: $class");
                            }
                            return (new $class())->handle($req, $next, ...$args);
                        };
                    },
                    $finalHandler
                );

                try {
                    $response = $handler($request);
                    if (is_array($response)) {
                        header("Content-Type: application/json");
                        echo json_encode($response);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Internal Server Error", "message" => "Handler must return an array"]);
                    }
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
            if (stripos($contentType, 'application/json') !== false) {
                $data = json_decode($rawInput, true);
                return (json_last_error() === JSON_ERROR_NONE && is_array($data)) ? $data : [];
            }
            if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                return $_POST ?? [];
            }
            if (stripos($contentType, 'multipart/form-data') !== false) {
                $data = $_POST ?? [];
                if (!empty($_FILES)) {
                    $data['files'] = $_FILES;
                }
                return $data;
            }
            if (empty($contentType) || !in_array(strtolower(trim($contentType)), ['application/json', 'application/x-www-form-urlencoded', 'multipart/form-data'])) {
                if (!empty($rawInput)) {
                    $data = json_decode($rawInput, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                        return $data;
                    }
                }
                return $_POST ?? [];
            }
        }
        return [];
    }
}