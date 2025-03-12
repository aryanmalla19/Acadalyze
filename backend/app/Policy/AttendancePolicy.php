<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\User;
use App\Models\Classes;

class AttendancePolicy implements Policy
{
    public function view($user, $model): bool
    {
        $class = Classes::find($model->class_id);
        if($class->school_id !== $user->school_id){
            return false;
        }
        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
    
    public function update($user, $model): bool
    {
        $class = Classes::find($model->class_id);
        if($class->school_id !== $user->school_id){
            return false;
        }

        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
}