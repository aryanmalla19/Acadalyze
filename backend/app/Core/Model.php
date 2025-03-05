<?php
namespace App\Core;

use PDO;
use PDOException;

class Model {
    protected PDO $db;
    protected array $errors = [];

    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die(json_encode(["message" => "Database connection failed: " . $e->getMessage()]));
        }
    }

    public function validate(array $data, array $rules): bool {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleSet = explode('|', $ruleSet); // Split rules (e.g., "required|email")

            foreach ($ruleSet as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $this->errors[$field][] = "The $field field is required.";
                }

                if ($rule === 'email' && !filter_var($data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "Invalid email format.";
                }

                if (str_starts_with($rule, 'min:')) {
                    $minLength = (int) explode(':', $rule)[1];
                    if (strlen($data[$field] ?? '') < $minLength) {
                        $this->errors[$field][] = "The $field must be at least $minLength characters.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $maxLength = (int) explode(':', $rule)[1];
                    if (strlen($data[$field] ?? '') > $maxLength) {
                        $this->errors[$field][] = "The $field must not exceed $maxLength characters.";
                    }
                }
            }
        }

        return empty($this->errors); // If no errors, return true
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
