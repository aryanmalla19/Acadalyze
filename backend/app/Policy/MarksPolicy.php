<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\User;

class MarksPolicy implements Policy
{
    public function view($user, $model): bool
    {
        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
    
    public function update($user, $model): bool
    {
        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
}