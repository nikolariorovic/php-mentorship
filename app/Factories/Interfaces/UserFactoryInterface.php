<?php
namespace App\Factories\Interfaces;

use App\Models\User;

interface UserFactoryInterface
{
    public static function create(array $data): User;
}