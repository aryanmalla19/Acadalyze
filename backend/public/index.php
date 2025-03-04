<?php
// Load Composer autoloader
require_once '../vendor/autoload.php';

// Load configuration
require_once '../config/config.php';

// Load the Router and start routing
use App\Core\Router;
$router = new Router();
$router->route();