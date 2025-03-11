<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Subject extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects WHERE subject_id = :subject_id");
        $stmt->execute([':subject_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllBySchoolId($id)
    {
        $stmt = $this->db->prepare("
            SELECT subjects.*, classes.class_name
            FROM subjects 
            LEFT JOIN classes ON subjects.class_id = classes.class_id 
            WHERE classes.school_id = :school_id
        ");
        $stmt->execute([':school_id' => $id]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ?: []; 
    }

    public function create($classId, $teacherId, $subjectName)
    {
        $stmt = $this->db->prepare("INSERT INTO subjects (class_id, teacher_id, subject_name) VALUES (:class_id, :teacher_id, :subject_name)");
        $success = $stmt->execute([':class_id' => $classId, ':teacher_id' => $teacherId, ':subject_name' => $subjectName]);
    
        return $success ? $this->db->lastInsertId() : false;
    }
    

    public function updateById($id, $data)
    {
        if (empty($data)) {
            return ['error' => 'No data provided for update'];
        }

        $allowedFields = ['class_id', 'subject_name', 'teacher_id'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return ['error' => 'No valid fields to update'];
        }

        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE subjects SET $setClause WHERE subject_id = :id";

        $stmt = $this->db->prepare($query);
        $params = array_merge([':id' => $id], array_combine(
            array_map(fn($key) => ":$key", array_keys($updateFields)),
            array_values($updateFields)
        ));

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM subjects WHERE subject_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }

}