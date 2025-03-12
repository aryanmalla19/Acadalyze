<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Marks extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM marks WHERE marks_id = :marks_id");
        $stmt->execute([':marks_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    public function getAllByClassId($id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM marks 
            LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
            LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
            WHERE marks.class_id = :class_id
        ");
        
        $stmt->execute([':class_id' => $id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }
    
    public function create($student_id, $subjects_exams_id, $marks_obtained)
    {
        $stmt = $this->db->prepare("INSERT INTO marks (student_id, subjects_exams_id, marks_obtained) VALUES (:student_id, :subjects_exams_id, :marks_obtained)");
        $success = $stmt->execute([':student_id' => $student_id, ':subjects_exams_id' => $subjects_exams_id, ':marks_obtained' => $marks_obtained]);
    
        return $success ? $this->db->lastInsertId() : false;
    }
    

    public function updateById($id, $data)
    {
        $allowedFields = ['student_id', 'subjects_exams_id', 'marks_obtained'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false;
        }

        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE marks SET $setClause WHERE marks_id = :id";

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
        $stmt = static::$pdo->prepare("SELECT * FROM marks WHERE marks_id = :marks_id LIMIT 1");
        $stmt->execute([':marks_id' => (int)$id]); // Cast to int for numeric IDs
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
        $stmt = $this->db->prepare("DELETE FROM marks WHERE marks_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }
}