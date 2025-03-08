<?php
// Load Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

header("Content-type: application/json; charset=UTF-8");

// Load configuration
require_once '../config/config.php';
use App\Core\Router;

use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\SchoolController;

$router = new Router();
$userController = new UserController();

$authController = new AuthController();
// $schoolController = new SchoolController();

$router->addRoute("POST", "/api/login", [$authController, "login"]);
$router->addRoute("POST", "/api/register", [$authController, "register"]);


$router->addRoute("GET", "/api/users", [$userController, "getAllUsers"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
]);

$router->addRoute("GET", "/api/users/{id}", [$userController, "getUserById"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]
]);

$router->addRoute("PUT", "/api/users/{id}", [$userController, "updateUser"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]
]);
$router->addRoute("DELETE", "/api/users/{id}", [$userController, "deleteUser"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]
]);

$router->route();