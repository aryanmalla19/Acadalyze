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
$schoolController = new SchoolController();

// AUTH
$router->addRoute("POST", "/api/login", [$authController, "login"]);
$router->addRoute("POST", "/api/register", [$authController, "register"]);


//USERS
$router->addRoute("GET", "/api/users", [$userController, "getAllUsersBySchoolId"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
]);

$router->addRoute('GET', '/api/users/{id}', [$userController, 'getUserById'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\UserPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\User::class]]]
]);

$router->addRoute("PUT", "/api/users/{id}", [$userController, "updateUser"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]

]);

$router->addRoute("DELETE", "/api/users/{id}", [$userController, "deleteUser"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]

]);


// SCHOOLS
$router->addRoute("GET", "/api/schools/{id}", [$schoolController, 'getSchool'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
]);

$router->addRoute("GET", "/api/schools", [$schoolController, 'getSchoolByUserId'], [
    [\App\Middleware\AuthMiddleware::class]
]);

$router->addRoute("POST", "/api/schools", [$schoolController, 'createSchool'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("PUT", '/api/schools/{id}', [$schoolController, 'updateSchool'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
]);

$router->addRoute("DELETE", '/api/schools/{id}', [$schoolController, 'deleteSchool'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
]);

$router->route();