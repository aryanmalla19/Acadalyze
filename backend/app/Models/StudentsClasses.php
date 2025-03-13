<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class StudentsClasses extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM student_classes WHERE student_class_id = :student_class_id");
        $stmt->execute([':student_class_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllBySchoolId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM student_classes s LEFT JOIN classes c ON c.class_id = s.class_id  WHERE c.school_id = :school_id");
        $stmt->execute([':school_id' => $id]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; 
    }

    public function create($student_id, $exam_id, $enrollment_date)
    {
        $stmt = $this->db->prepare("INSERT INTO student_classes (student_id, exam_id, enrollment_date) VALUES (:student_id, :exam_id, :enrollment_date)");
        $success = $stmt->execute([':student_id' => $student_id, ':exam_id' => $exam_id, ':enrollment_date' => $enrollment_date]);
    
        return $success ? $this->db->lastInsertId() : false;
    }
    

    public function updateById($id, $data)
    {
        $allowedFields = ['student_id', 'class_id', 'enrollment_date'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false;
        }
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE student_classes SET $setClause WHERE student_class_id = :id";
        
        $stmt = $this->db->prepare($query);
        $params = array_merge([':id' => $id], array_combine(
            array_map(fn($key) => ":$key", array_keys($updateFields)),
            array_values($updateFields)
        ));

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }


    public static function find(string $id): ?static
    {
        $stmt = static::$pdo->prepare("SELECT * FROM student_classes WHERE student_class_id = :student_class_id LIMIT 1");
        $stmt->execute([':student_class_id' => (int)$id]); // Cast to int for numeric IDs
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

    public function deleteById($id)
    {
        $stmt = $this->db->prepare("DELETE FROM student_classes WHERE student_class_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }

}