<?php
namespace App\Policy;
use App\Core\Policy;

class UserPolicy implements Policy
{
    public function view($user, $model): bool
    {
        if($user->role_name == 'Admin'){
            return $user->school_id = $model->school_id;
        }
        return $user->user_id === $model->user_id;
    }
    
    public function update($user, $model): bool
    {
        return $user->user_id === $model->user_id;
    }
}