<?php
// Load Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

// List of allowed origins for development (you can add more ports as needed)
$allowed_origins = [
    'http://localhost:5174',
    'http://localhost:3000',
    'http://localhost:5175',
    'http://localhost:5173',
    'http://localhost:5176',
];
// Get the origin of the incoming request
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
// Check if the origin is in the allowed list
if (in_array($origin, $allowed_origins)) {
    // Allow the request from the allowed origin
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Optionally, you can block requests from non-allowed origins
    header("Access-Control-Allow-Origin: 'none'"); // Or handle error
}
// Allow credentials (cookies, HTTP authentication)
header("Access-Control-Allow-Credentials: true");
// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Allow specific headers (Content-Type, Authorization, etc.)
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respond with 200 status code for OPTIONS requests
    http_response_code(200);
    exit();
}

// Load configuration
require_once '../config/config.php';
use App\Core\Router;

use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\SchoolController;
use App\Controllers\ClassesController;
use App\Controllers\SubjectController;


$router = new Router();

$userController = new UserController();
$authController = new AuthController();
$schoolController = new SchoolController();
$classesController = new ClassesController();
$subjectController = new SubjectController();

// AUTH
$router->addRoute("POST", "/api/auth/login", [$authController, "login"]);
$router->addRoute("POST", "/api/auth/register", [$authController, "register"]);
$router->addRoute("GET", "/api/auth/verify", [$authController, "verify"]);
$router->addRoute("POST", "/api/auth/logout", [$authController, "logout"]);

//USERS
$router->addRoute("GET", "/api/users", [$userController, "index"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
]);

$router->addRoute('GET', '/api/users/{id}', [$userController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\UserPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\User::class]]]
]);

$router->addRoute("PUT", "/api/users/{id}", [$userController, "update"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]
    
]);

$router->addRoute("DELETE", "/api/users/{id}", [$userController, "destroy"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [\App\Policy\UserPolicy::class]]

]);


// SCHOOLS
$router->addRoute("GET", "/api/schools/{id}", [$schoolController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
]);

$router->addRoute("GET", "/api/schools", [$schoolController, 'index'], [
    [\App\Middleware\AuthMiddleware::class]
]);

$router->addRoute("POST", "/api/schools", [$schoolController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("PUT", '/api/schools/{id}', [$schoolController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
]);

$router->addRoute("DELETE", '/api/schools/{id}', [$schoolController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\School::class]]]
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