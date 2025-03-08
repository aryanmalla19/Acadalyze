<?php
namespace App\Policy;
use App\Core\Policy;
use App\Models\User;

class UserPolicy
{
    public function view(User $authUser, User $target): bool
    {
        if ($authUser->role === 'Admin') {
            return $authUser->school_id === $target->school_id;
        }
        return $authUser->user_id === $target->user_id;
    }
}