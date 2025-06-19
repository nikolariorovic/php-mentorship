<?php
namespace App\Factories;

use App\Factories\Interfaces\UserFactoryInterface;
use App\Models\User;

class UserFactory implements UserFactoryInterface
{
    public static function create(array $data): User {
        return User::create($data);
    }
}