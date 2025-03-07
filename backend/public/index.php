<?php
// Load Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

// Rest of your code goes here...
set_exception_handler([App\Core\ErrorHandler::class, 'handleException']);

header("Content-type: application/json; charset=UTF-8");

// Load configuration
require_once '../config/config.php';

use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;

$router = new Router();
$userController = new UserController();
$authController = new AuthController();

// $router->addRoute("POST", "/api/users", [$userController, "createUser"]);
$router->addRoute("PUT", "/api/users/{id}", [$userController, "updateUser"]);

// Public Routes
$router->addRoute("POST", "/api/login", [$authController, "login"]);
$router->addRoute("POST", "/api/register", [$authController, "register"]);


// Protected Routes (Require Authentication)
// $router->addRoute("GET", "/api/profile", [$authController, "getProfile"], ["App\Middleware\AuthMiddleware"]);

// Admin-Only Routes
$router->addRoute("GET", "/api/users", [$userController, "getAllUsers"], [
    ["App\Middleware\RoleMiddleware", ["Admin"]] // Only 'admin' can access
]);

// User & Admin Access (Only registered users)
$router->addRoute("GET", "/api/users/{id}", [$userController, "getUserById"], [
    ["App\Middleware\AuthMiddleware"],
    ["App\Middleware\UserAccessMiddleware"]
]);

$router->addRoute('DELETE', "/api/users/{id}", [$userController, "deleteUser"], [
    ["App\Middleware\AuthMiddleware"],
    ["App\Middleware\UserAccessMiddleware"]
]);

$router->route();