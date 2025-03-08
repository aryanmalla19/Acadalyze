<?php
namespace App\Models;
use App\Core\Model;

class School extends Model
{
    public function getAllSchools()
    {
        $stmt = $this->db->query("SELECT * FROM schools");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?? null;
    }

    public function getSchoolById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM schools WHERE school_id = :school_id");
        $stmt->bindParam(':school_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?? null;
    }

    public function createSchool($schoolName, $schoolEmail, $establishedDate, $telephone, $address)
    {
        $stmt = $this->db->prepare("INSERT INTO schools 
            (school_name, school_email, established_date, telephone_number, address) 
            VALUES (:school_name, :school_email, :established_date, :telephone_number, :address)");

        $stmt->bindParam(':school_name', $schoolName);
        $stmt->bindParam(':school_email', $schoolEmail);
        $stmt->bindParam(':established_date', $establishedDate);
        $stmt->bindParam(':telephone_number', $telephone);
        $stmt->bindParam(':address', $address);

        $stmt->execute(); // Returns true if successful
        return $this->db->lastInsertId();
    }

    public function updateSchool($id, $schoolName, $schoolEmail, $establishedDate, $telephone, $address)
    {
        $stmt = $this->db->prepare("UPDATE schools SET 
            school_name = :school_name, 
            school_email = :school_email, 
            established_date = :established_date, 
            telephone_number = :telephone_number, 
            address = :address 
            WHERE school_id = :school_id");

        $stmt->bindParam(':school_id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':school_name', $schoolName);
        $stmt->bindParam(':school_email', $schoolEmail);
        $stmt->bindParam(':established_date', $establishedDate);
        $stmt->bindParam(':telephone_number', $telephone);
        $stmt->bindParam(':address', $address);

        return $stmt->execute(); // Returns true if successful
    }


    public static function find(string $id): ?static
    {
        if (!isset(static::$pdo)) {
            throw new \RuntimeException("Database connection not initialized");
        }

        $stmt = static::$pdo->prepare("SELECT * FROM schools WHERE school_id = :school_id LIMIT 1");
        $stmt->execute([':school_id' => (int)$id]); // Cast to int for numeric IDs
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

    public function getSchoolByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM schools WHERE school_email = :email");
        $stmt->execute([
            ':email' => $email,
        ]);
        $school = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $school ?: false;
    }




    public function deleteSchool($id)
    {
        $stmt = $this->db->prepare("DELETE FROM schools WHERE school_id = :school_id");
        $stmt->bindParam(':school_id', $id, \PDO::PARAM_INT);
        return $stmt->execute(); // Returns true if successful
    }
}
