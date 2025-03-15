<?php
namespace App\Policy;

use App\Core\Policy;

class ClassesPolicy implements Policy
{
    public function view($user, $model): bool
    {
        return $user->school_id === $model->school_id;
    }
    
    public function update($user, $model): bool
    {
        if(!empty($model->class_teacher_id)){
            $classTeacher = School::find($model->class_teacher_id);
            return $user->school_id === $model->school_id && $user->school_id === $classTeacher->school_id;
        }
        return $user->school_id === $model->school_id;
    }
}