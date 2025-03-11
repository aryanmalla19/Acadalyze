<?php
namespace App\Policy;
use App\Core\Policy;

class SchoolPolicy implements Policy
{
    public function view($user, $model) 
    {
        return $user->school_id === $model->school_id;
    }
    
    public function update($user, $model) 
    {
        return $user->school_id === $model->school_id;
    }
}