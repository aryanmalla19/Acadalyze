<?php
namespace App\Models;
use App\Core\Model;

class User extends Model {
    // Get all users
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT u.*, NULL as password, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.role_id");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get a user by ID
    public function getUserById($id): bool|array
    {
        $stmt = $this->db->prepare("SELECT *, NULL as password FROM users WHERE user_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: false;
    }


    public function createUser(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, password, username, first_name, last_name, address, phone_number, parent_phone_number, date_of_birth ) VALUES (:email, :password, :username, :first_name, :last_name, :address, :phone_number, :parent_phone_number, :date_of_birth)");
        return $stmt->execute([
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':username' => $data['username'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':address' => $data['address'],
            ':phone_number' => $data['phone_number'],
            ':parent_phone_number' => $data['parent_phone_number'],
            ':date_of_birth' => $data['date_of_birth'],
        ]);
    }

    // Update user details (no validation here, just DB logic)
    public function updateUser($id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE user_id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email']
        ]);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([
            ':email' => $email,
        ]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: false;
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([
            ':username' => $username,
        ]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: false;
    }

    public function getUserByIdentifier(string $identifier)
    {
        $sql = "SELECT u.*, r.role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.role_id 
                WHERE u.email = :identifier OR u.username = :identifier LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteUser($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result && $stmt->rowCount() > 0;
    }
}
