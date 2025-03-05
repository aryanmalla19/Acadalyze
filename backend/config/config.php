<?php
define('BASE_URL', 'http://localhost:8080/'); // Adjust to your setup
define('DB_HOST', getenv('MYSQL_HOST') ?: 'acadalyze-mysql'); // Fallback to service name
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'root');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'acadalyze');

define('JWT_SECRET', 'my_super_secret_key'); // Change this to a secure key
define('JWT_ALGO', 'HS256'); // Algorithm used for signing JWT
define('JWT_EXPIRATION', 3600); // Token expiration time (1 hour)