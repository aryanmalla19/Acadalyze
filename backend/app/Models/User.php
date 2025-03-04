<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function getAllUsers() {
        // Example query (create a table 'users' in your DB for this to work)
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}