<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Exam extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE exam_id = :exam_id");
        $stmt->execute([':exam_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllBySchoolId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE school_id = :school_id");
        $stmt->execute([':school_id' => $id]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ?: []; 
    }

    public function create($schoolId, $examName, $examDate)
    {
        $stmt = $this->db->prepare("INSERT INTO exams (school_id, exam_name, exam_date) VALUES (:school_id, :exam_name, :exam_date)");
        $success = $stmt->execute([':school_id' => $schoolId, ':exam_name' => $examName, ':exam_date' => $examDate]);
    
        return $success ? $this->db->lastInsertId() : false;
    }
    

    public function updateById($id, $data)
    {
        $allowedFields = ['school_id', 'exam_name', 'exam_date'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false;
        }

        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE exams SET $setClause WHERE exam_id = :id";

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
        $stmt = static::$pdo->prepare("SELECT * FROM exams WHERE exam_id = :exam_id LIMIT 1");
        $stmt->execute([':exam_id' => (int)$id]); // Cast to int for numeric IDs
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
        $stmt = $this->db->prepare("DELETE FROM exams WHERE exam_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }

}