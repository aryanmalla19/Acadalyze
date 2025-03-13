<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\Classes;
use App\Models\User;

class StudentsClassesPolicy implements Policy
{
    public function view($user, $model): bool
    {
        $student = User::find($model->student_id);
        if($student->school_id !== $user->school_id){
            return false;
        }
        $class = Classes::find($model->class_id);
        if($class->school_id !== $user->school_id){
            return false;
        }
        return true;
    }
    
    public function update($user, $model): bool
    {
        $student = User::find($model->student_id);
        if($student->school_id !== $user->school_id){
            return false;
        }
        $class = Classes::find($model->class_id);
        if($class->school_id !== $user->school_id){
            return false;
        }
        return true;
    }
}