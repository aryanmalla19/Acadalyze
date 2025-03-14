<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class SubjectsExams extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects_exams s LEFT JOIN exams e ON e.exam_id = s.exam_id LEFT JOIN subjects sub ON sub.subject_id = s.subject_id  WHERE subjects_exams_id = :subjects_exams_id");
        $stmt->execute([':subjects_exams_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllBySchoolId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects_exams s LEFT JOIN exams e ON e.exam_id = s.exam_id LEFT JOIN subjects sub ON sub.subject_id = s.subject_id  WHERE e.school_id = :school_id");
        $stmt->execute([':school_id' => $id]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; 
    }

    public function getAllBySchoolIdAndExamId($id, $examId)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects_exams s LEFT JOIN exams e ON e.exam_id = s.exam_id LEFT JOIN subjects sub ON sub.subject_id = s.subject_id  WHERE e.school_id = :school_id AND s.exam_id =:exam_id");
        $stmt->execute([':school_id' => $id, ':exam_id'=>$examId]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; 
    }

    public function getAllBySchoolIdAndSubjectId($id, $subjectId)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects_exams s LEFT JOIN exams e ON e.exam_id = s.exam_id LEFT JOIN subjects sub ON sub.subject_id = s.subject_id  WHERE e.school_id = :school_id AND s.subject_id =:subject_id");
        $stmt->execute([':school_id' => $id, ':subject_id'=>$subjectId]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; 
    }

    public function getAllBySchoolIdSubjectIdExamId($id, $subjectId, $examId)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects_exams s LEFT JOIN exams e ON e.exam_id = s.exam_id LEFT JOIN subjects sub ON sub.subject_id = s.subject_id  WHERE e.school_id = :school_id AND s.subject_id =:subject_id AND s.exam_id =:exam_id");
        $stmt->execute([':school_id' => $id, ':subject_id'=>$subjectId, ':exam_id'=>$examId]);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: []; 
    }

    public function create($subject_id, $exam_id, $subject_exam_time, $pass_marks, $full_marks)
    {
        $stmt = $this->db->prepare("INSERT INTO subjects_exams (subject_id, exam_id, subject_exam_time, pass_marks, full_marks) VALUES (:subject_id, :exam_id, :subject_exam_time, :pass_marks, :full_marks)");
        $success = $stmt->execute([':subject_id' => $subject_id, ':exam_id' => $exam_id, ':subject_exam_time' => $subject_exam_time, 'pass_marks' => $pass_marks, 'full_marks'=>$full_marks]);
    
        return $success ? $this->db->lastInsertId() : false;
    }

    public function updateById($id, $data)
    {
        $allowedFields = ['subject_exam_time', 'pass_marks', 'full_marks'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false;
        }
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE subjects_exams SET $setClause WHERE subjects_exams_id = :id";
        
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
        $stmt = static::$pdo->prepare("SELECT * FROM subjects_exams WHERE subjects_exams_id = :subjects_exams_id LIMIT 1");
        $stmt->execute([':subjects_exams_id' => (int)$id]); // Cast to int for numeric IDs
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
        $stmt = $this->db->prepare("DELETE FROM subjects_exams WHERE subjets_exams_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }

}