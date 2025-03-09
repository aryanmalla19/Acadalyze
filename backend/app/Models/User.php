<?php
namespace App\Models;
use App\Core\Model;

class User extends Model {
    protected static string $table = 'users';
    // Get all users
    public function getAllUsersBySchoolId($school_id): array
    {
        $stmt = $this->db->prepare("SELECT u.*, NULL as password, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.role_id WHERE u.school_id = :school_id");
        $stmt->execute([':school_id'=> $school_id]);
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

    public static function find(string $id): ?static
    {
        // Ensure PDO is set
        if (!isset(static::$pdo)) {
            throw new \RuntimeException("Database connection not initialized");
        }

        $stmt = static::$pdo->prepare("SELECT * , NULL as password, r.role_name
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.role_id WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => (int)$id]); // Cast to int for numeric IDs
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }
        
        // Create an instance and populate it
        $instance = new static();
        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }
        return $instance;
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
    public function updateUser(string $id, array $data): bool
    {
        if (empty($data)) {
            return false; // Nothing to update
        }

        // Define allowed fields to prevent updating sensitive columns (e.g., role, school_id)
        $allowedFields = ['first_name', 'last_name', 'address', 'date_of_birth', 'phone_number', 'parent_phone_number', 'email', 'username']; // Adjust based on your schema
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false; // No valid fields to update
        }

        // Build the SET clause dynamically
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE users SET $setClause WHERE user_id = :id";

        $stmt = $this->db->prepare($query);
        $params = array_merge([':id' => $id], array_combine(
            array_map(fn($key) => ":$key", array_keys($updateFields)),
            array_values($updateFields)
        ));

        return $stmt->execute($params);
    }

    public function setSchoolId($userId, $schoolId): bool
    {
        $query = "UPDATE users SET school_id = :school_id WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':school_id' => $schoolId, ':user_id' => $userId]);
    }
    

    public function getSchoolByUserId($userId): int | bool
    {
        $stmt = $this->db->prepare("SELECT school_id FROM users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        
        $schoolId = $stmt->fetchColumn(); // Fetches a single column value
    
        return $schoolId??false;
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

    public function countUsersBySchoolId($school_id)
    {
        // Prepare the SQL query to count users by school_id
        $sql = "SELECT COUNT(*) FROM users WHERE school_id = :school_id";

        // Execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, \PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result, which is a single row with a single value (the count)
        $count = $stmt->fetchColumn();

        // Return the count of users
        return $count;
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
