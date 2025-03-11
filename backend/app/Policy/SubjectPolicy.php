<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\Classes;

class SubjectPolicy implements Policy
{
    public function view($user, $model) 
    {
        $class = Classes::find($model->class_id);
        return $user->school_id === $class->school_id;
    }
    
    public function update($user, $model) 
    {
        $class = Classes::find($model->class_id);
        $teacher = Classes::find($model->teacher_id);
        return $user->school_id === $class->school_id && $user->school_id === $teacher->school_id;
    }
}