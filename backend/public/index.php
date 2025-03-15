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
header('Content-Type: application/json; charset=UTF-8');


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
use App\Controllers\ExamController;
use App\Controllers\SubjectsExamsController;
use App\Controllers\MarksController;
use App\Controllers\AttendanceController;
use App\Controllers\StudentClassesController;


$router = new Router();

$userController = new UserController();
$authController = new AuthController();
$schoolController = new SchoolController();
$classesController = new ClassesController();
$subjectController = new SubjectController();
$examController = new ExamController();
$subjectsExamsController = new SubjectsExamsController();
$marksController = new MarksController();
$attendanceController = new AttendanceController();
$studentClassesController = new StudentClassesController();


// AUTH
$router->addRoute("POST", "/api/auth/login", [$authController, "login"]);
$router->addRoute("POST", "/api/auth/register", [$authController, "register"]);
$router->addRoute("GET", "/api/auth/verify", [$authController, "verify"]);
$router->addRoute("POST", "/api/auth/logout", [$authController, "logout"]);

//USERS
$router->addRoute("GET", "/api/users", [$userController, "index"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute('GET', '/api/users/{id}', [$userController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\UserPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\User::class]]]
]);

$router->addRoute("PUT", "/api/users/{id}", [$userController, "update"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\UserPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\User::class]]]
]);

$router->addRoute("DELETE", "/api/users/{id}", [$userController, "destroy"], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\UserPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\User::class]]]

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
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\School::class]]]
]);

$router->addRoute("DELETE", '/api/schools/{id}', [$schoolController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SchoolPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\School::class]]]
]);

// CLASSES
$router->addRoute("GET", '/api/classes', [$classesController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);


$router->addRoute("GET", '/api/classes/{id}', [$classesController, 'show'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Classes::class]]]
]);

$router->addRoute("POST", '/api/classes', [$classesController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]]
]);

$router->addRoute("PUT", '/api/classes/{id}', [$classesController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Classes::class]]]
]);

$router->addRoute("DELETE", '/api/classes/{id}', [$classesController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ClassesPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Classes::class]]]
]);

// SUBJECTS
$router->addRoute("GET", '/api/subjects', [$subjectController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
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
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Subject::class]]]
]);


// EXAMS
$router->addRoute("GET", '/api/exams', [$examController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("GET", '/api/exams/{id}', [$examController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ExamPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Exam::class]]]
]);

$router->addRoute("POST", '/api/exams', [$examController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
]);

$router->addRoute("PUT", '/api/exams/{id}', [$examController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ExamPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Exam::class]]]
]);

$router->addRoute("DELETE", '/api/exams/{id}', [$examController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\ExamPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Exam::class]]]
]);


// SUBJECTS_EXAMS
$router->addRoute("GET", '/api/subject-exams', [$subjectsExamsController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("GET", '/api/subject-exams/{id}', [$subjectsExamsController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectsExamsPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\SubjectsExams::class]]]
]);

$router->addRoute("POST", '/api/subject-exams', [$subjectsExamsController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("PUT", '/api/subject-exams/{id}', [$subjectsExamsController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectsExamsPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\SubjectsExams::class]]]
]);

$router->addRoute("DELETE", '/api/subject-exams/{id}', [$subjectsExamsController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\SubjectsExamsPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\SubjectsExams::class]]]
]);


// MARkS
$router->addRoute("GET", '/api/marks', [$marksController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("GET", '/api/marks/{id}', [$marksController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\MarksPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Marks::class]]]
]);

$router->addRoute("POST", '/api/marks', [$marksController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("PUT", '/api/marks/{id}', [$marksController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\MarksPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Marks::class]]]
]);

$router->addRoute("DELETE", '/api/marks/{id}', [$marksController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\MarksPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Marks::class]]]
]);


// ATTENDANCE
$router->addRoute("GET", '/api/attendance', [$attendanceController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("GET", '/api/attendance/{id}', [$attendanceController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\AttendancePolicy::class, 'action' => 'view', 'modelClass' => \App\Models\Attendance::class]]]
]);

$router->addRoute("POST", '/api/attendance', [$attendanceController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("PUT", '/api/attendance/{id}', [$attendanceController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\AttendancePolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Attendance::class]]]
]);

$router->addRoute("DELETE", '/api/attendance/{id}', [$attendanceController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\AttendancePolicy::class, 'action' => 'update', 'modelClass' => \App\Models\Attendance::class]]]
]);



// STUDENT_CLASSES
$router->addRoute("GET", '/api/student-classes', [$studentClassesController, 'index'], [
    [\App\Middleware\AuthMiddleware::class],
]);

$router->addRoute("GET", '/api/student-classes/{id}', [$studentClassesController, 'show'], [
    [\App\Middleware\AuthMiddleware::class], 
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\StuentsClassesPolicy::class, 'action' => 'view', 'modelClass' => \App\Models\StudentsClasses::class]]]
]);

$router->addRoute("POST", '/api/student-classes', [$studentClassesController, 'create'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
]);

$router->addRoute("PUT", '/api/student-classes/{id}', [$studentClassesController, 'update'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\StuentsClassesPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\StudentsClasses::class]]]
]);

$router->addRoute("DELETE", '/api/student-classes/{id}', [$studentClassesController, 'destroy'], [
    [\App\Middleware\AuthMiddleware::class],
    [\App\Middleware\RoleMiddleware::class, [['Admin', 'Teacher']]],
    [\App\Middleware\AccessMiddleware::class, [['policy' => \App\Policy\StuentsClassesPolicy::class, 'action' => 'update', 'modelClass' => \App\Models\StudentsClasses::class]]]
]);

$router->route();