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
    public function getAllByClassId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE marks.class_id = :class_id");
        
        $stmt->execute([':class_id' => $id]);
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