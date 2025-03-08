<?php
namespace App\Core;

interface Policy
{
    public function view(User $authUser, $model): bool;
}