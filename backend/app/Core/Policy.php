<?php
namespace App\Core;

interface Policy
{
    public function view($authUser, $model): bool;
    public function update($authUser, $model): bool;
}