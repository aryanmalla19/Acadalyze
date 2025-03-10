<?php
namespace App\Models;

use App\Core\Model;

class Classes extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE class_id = :class_id LIMIT 1");
        $stmt->execute([':class_id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC)??null;
    }

    public function getAllBySchoolId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE school_id = :school_id");
        $stmt->execute([':school_id' => $id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC)??null;  
    }

    public function create($classTeacherId, $schoolId, $className, $section)
    {
        $stmt = $this->db->prepare("INSERT INTO classes (class_teacher_id, school_id, class_name, section) 
                                    VALUES (:class_teacher_id, :school_id, :class_name, :section)");
        
        $stmt->execute([
            ':class_teacher_id' => $classTeacherId,
            ':school_id' => $schoolId,
            ':class_name' => $className,
            ':section' => $section
        ]);
    
        return $this->db->lastInsertId() ?: null;
    }
    
    public function updateById($id, $data)
    {
        if (empty($data)) {
            return false; // Nothing to update
        }

        // Define allowed fields to prevent updating sensitive columns (e.g., role, school_id)
        $allowedFields = ['class_teacher_id', 'class_name', 'section']; // Adjust based on your schema
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false; // No valid fields to update
        }

        // Build the SET clause dynamically
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE classes SET $setClause WHERE class_id = :id";

        $stmt = $this->db->prepare($query);
        $params = array_merge([':id' => $id], array_combine(
            array_map(fn($key) => ":$key", array_keys($updateFields)),
            array_values($updateFields)
        ));

        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE class_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result && $stmt->rowCount() > 0;
    }


    public static function find(string $id): ?static
    {
        if (!isset(static::$pdo)) {
            throw new \RuntimeException("Database connection not initialized");
        }

        $stmt = static::$pdo->prepare("SELECT * FROM classes WHERE class_id = :class_id LIMIT 1");
        $stmt->execute([':class_id' => (int)$id]); // Cast to int for numeric IDs
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

    public function getByTeacherId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE class_teacher_id = :class_teacher_id LIMIT 1");
        $stmt->execute([':class_teacher_id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC)??null;
    }


}