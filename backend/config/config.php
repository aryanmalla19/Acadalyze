<?php
define('BASE_URL', 'http://localhost:8080/'); // Adjust to your setup
define('DB_HOST', getenv('MYSQL_HOST') ?: 'acadalyze-mysql'); // Fallback to service name
define('DB_USER', getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'root');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'acadalyze');