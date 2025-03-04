<?php
namespace App\Core;

class Router {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if controller exists
        if (isset($url[0]) && file_exists("../app/Controllers/" . ucfirst($url[0]) . "Controller.php")) {
            $this->controller = ucfirst($url[0]) . "Controller";
            unset($url[0]);
        }

        // Instantiate controller
        $controllerClass = "App\\Controllers\\" . $this->controller;
        $this->controller = new $controllerClass;

        // Check if method exists
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Get parameters
        $this->params = $url ? array_values($url) : [];
    }

    public function route() {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        return explode('/', filter_var($url, FILTER_SANITIZE_URL));
    }
}