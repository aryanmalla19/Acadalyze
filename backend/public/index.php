<?php
// Load Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load configuration
require_once '../config/config.php';
use App\Core\Router;

use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\SchoolController;
use App\Controllers\RoleController;
use App\Controllers\ClassesController;
use App\Controllers\SubjectController;


$router = new Router();

$userController = new UserController();
$authController = new AuthController();
$schoolController = new SchoolController();
$roleController = new RoleController();
$classesController = new ClassesController();
$subjectController = new SubjectController();

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

// ROLES
$router->addRoute("GET", '/api/roles/{id}', [$roleController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("POST", '/api/roles/{id}', [$roleController, 'create'], [
    [\App\Middleware\AuthMiddleware::class]
]);

// CLASSES
$router->addRoute("GET", '/api/classes', [$classesController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
]);


$router->addRoute("GET", '/api/classes/{id}', [$classesController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Classes::class]]]
]);

$router->addRoute("POST", '/api/classes', [$classesController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]]
]);

$router->addRoute("PUT", '/api/classes/{id}', [$classesController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Classes::class]]]
]);

$router->addRoute("DELETE", '/api/classes/{id}', [$classesController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Classes::class]]]
]);

// SUBJECTS
$router->addRoute("GET", '/api/subjects', [$subjectController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("GET", '/api/subjects/{id}', [$subjectController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Subject::class]]]
]);

$router->addRoute("POST", '/api/subjects', [$subjectController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("PUT", '/api/subjects/{id}', [$subjectController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Subject::class]]]
]);

$router->addRoute("DELETE", '/api/subjects/{id}', [$subjectController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Subject::class]]]
]);

$router->route();