<?php
namespace App\Models;
use App\Core\Model;

class School extends Model
{
    public function getAllSchools()
    {
    $stmt = $this->db->query("SELECT * FROM schools");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC)??null;
    }
    
}