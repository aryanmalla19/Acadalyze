<?php
namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    // Fetch a role by ID
    public function read($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE role_id = :role_id");
        $stmt->bindParam(':role_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?? null;
    }

    // Create a new role
    public function create($roleName)
    {
        $stmt = $this->db->prepare("INSERT INTO roles (role_name) VALUES (:role_name)");
        return $stmt->execute([':role_name' => $roleName]);
    }

    public function getIdByRole($roleName)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE role_name = :role_name LIMIT 1");
        $stmt->execute([':role_name' => $roleName]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?? null;
    }

}
