<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Marks extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT m.*, u.first_name, u.last_name, s.subject_name, se.pass_marks, se.full_marks, s.subject_name FROM marks m LEFT JOIN users u ON m.student_id = u.user_id LEFT JOIN subjects_exams se ON se.subjects_exams_id = m.subjects_exams_id LEFT JOIN subjects s ON s.subject_id = se.subject_id WHERE marks_id = :marks_id");
        $stmt->execute([':marks_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    
    public function getAllBySubjectsExamId($schoolId, $subjectsExamsId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.subjects_exams_id = :subjects_exams_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':subjects_exams_id'=> $subjectsExamsId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByClassId($schoolId, $classId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND subjects.class_id = :class_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':class_id'=> $classId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByClassIdAndExamId($schoolId, $classId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND subjects.class_id = :class_id AND subjects_exams.exam_id =:exam_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':class_id'=> $classId, ':exam_id' => $examId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentAndExamId($schoolId, $studentId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects_exams.exam_id =:exam_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $classId, ':exam_id' => $examId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }
    

    public function getAllByStudentSubjectsExamsAndExamId($schoolId, $studentId, $subjectsExamsId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects_exams.exam_id =:exam_id AND marks.subjects_exams_id = :subjects_exams_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':exam_id' => $examId, ':subjects_exams_id' => $subjectsExamsId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }
    
    public function getAllByStudentClassAndExamId($schoolId, $studentId, $classId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects_exams.exam_id =:exam_id AND subjects_exams.class_id = :class_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':exam_id' => $examId, ':class_id' => $examId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentClassSubjectsExamsAndExamId($schoolId, $studentId, $classId, $subjectsExamsId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects_exams.exam_id =:exam_id AND subjects_exams.class_id = :class_id AND marks.subjects_exams_id = :subjects_exams_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':exam_id' => $examId, ':class_id' => $examId, ':subjects_exams_id' => $subjectsExamsId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentId($schoolId, $studentId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }


    public function getAllByExamId($schoolId, $examId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND subjects_exams.exam_id = :exam_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':exam_id'=> $examId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentAndSubjectsExams($schoolId, $studentId, $subjectsExamsId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND marks.subjects_exams_id =:subjects_exams_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':subjects_exams_id' => $subjectsExamsId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentAndClass($schoolId, $studentId, $classId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects.class_id =:class_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':class_id' => $classId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllByStudentClassSubjectsExams($schoolId, $studentId, $classId, $subjectsExamsId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM marks 
        LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
        LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
        LEFT JOIN classes ON classes.class_id = subjects.class_id 
        WHERE classes.school_id = :school_id AND marks.student_id = :student_id AND subjects.class_id =:class_id AND marks.subjects_exams_id = :subjects_exams_id
    ");
    
    $stmt->execute([':school_id' => $schoolId, ':student_id'=> $studentId, ':class_id' => $classId, ':subjects_exams_id' => $subjectsExamsId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; 
    }

    public function getAllBySchoolId($schoolId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM marks 
            LEFT JOIN subjects_exams ON marks.subjects_exams_id = subjects_exams.subjects_exams_id 
            LEFT JOIN subjects ON subjects.subject_id = subjects_exams.subject_id 
            LEFT JOIN classes ON classes.class_id = subjects.class_id 
            WHERE classes.school_id = :school_id 
        ");
        
        $stmt->execute([':school_id' => $schoolId]);
        
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