<?php
namespace App\Core;

class Model {
    protected $db;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->db = new \PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "Database connection successful!";
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}