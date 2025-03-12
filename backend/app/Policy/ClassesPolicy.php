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
        return $user->school_id === $model->school_id;
    }
}