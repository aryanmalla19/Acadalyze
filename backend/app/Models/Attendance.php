<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Attendance extends Model
{
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE attendance_id = :attendance_id");
        $stmt->execute([':attendance_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAllBySchoolIdAndDateRange($schoolId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare(
            "SELECT * 
             FROM attendance a 
             LEFT JOIN classes c ON a.class_id = c.class_id 
             WHERE c.school_id = :school_id 
               AND a.attendance_date BETWEEN :start_date AND :end_date"
        );
        
        $stmt->execute([
            ':school_id'  => $schoolId,
            ':start_date' => $startDate,
            ':end_date'   => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    public function getByClassAndDateRange($schoolId, $classId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare(
            "SELECT * 
             FROM attendance a 
             LEFT JOIN classes c ON a.class_id = c.class_id 
             WHERE c.school_id = :school_id 
               AND a.class_id = :class_id 
               AND a.attendance_date BETWEEN :start_date AND :end_date"
        );
        
        $stmt->execute([
            ':school_id'  => $schoolId,
            ':class_id'   => $classId,
            ':start_date' => $startDate,
            ':end_date'   => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    public function getByStudentAndDateRange($schoolId, $studentId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare(
            "SELECT * 
             FROM attendance a 
             LEFT JOIN classes c ON a.class_id = c.class_id 
             WHERE c.school_id = :school_id 
               AND a.student_id = :student_id 
               AND a.attendance_date BETWEEN :start_date AND :end_date"
        );
        
        $stmt->execute([
            ':school_id'  => $schoolId,
            ':student_id' => $studentId,
            ':start_date' => $startDate,
            ':end_date'   => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    public function getByStudentClassAndDateRange($schoolId, $studentId, $classId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare(
            "SELECT * 
             FROM attendance a 
             LEFT JOIN classes c ON a.class_id = c.class_id 
             WHERE c.school_id = :school_id 
               AND a.student_id = :student_id 
               AND a.class_id = :class_id 
               AND a.attendance_date BETWEEN :start_date AND :end_date"
        );
        
        $stmt->execute([
            ':school_id'  => $schoolId,
            ':student_id' => $studentId,
            ':class_id'   => $classId,
            ':start_date' => $startDate,
            ':end_date'   => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    
    public function create($student_id, $class_id, $attendance_date, $status)
    {
        $stmt = $this->db->prepare("INSERT INTO attendance (student_id, class_id, attendance_date, status) VALUES (:student_id, :class_id, :attendance_date, :status)");
        $success = $stmt->execute([':student_id' => $student_id, ':class_id' => $class_id, ':attendance_date' => $attendance_date, ':status' => $status]);
    
        return $success ? $this->db->lastInsertId() : false;
    }
    

    public function updateById($id, $data)
    {
        $allowedFields = ['attendance_date', 'status'];
        $updateFields = array_intersect_key($data, array_flip($allowedFields));

        if (empty($updateFields)) {
            return false;
        }

        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
        $query = "UPDATE attendance SET $setClause WHERE attendance_id = :id";

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
        $stmt = static::$pdo->prepare("SELECT * FROM attendance WHERE attendance_id = :attendance_id LIMIT 1");
        $stmt->execute([':attendance_id' => (int)$id]); // Cast to int for numeric IDs
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
        $stmt = $this->db->prepare("DELETE FROM attendance WHERE attendance_id = :id");
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result && $stmt->rowCount() > 0;
    }
}