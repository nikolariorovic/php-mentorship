<?php
namespace App\Factories;

use App\Factories\Interfaces\UserFactoryInterface;
use App\Models\Admin;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\User;

class UserFactory implements UserFactoryInterface
{
    public static function create(array $data): User
    {
        return match($data['role']) {
            'admin' => new Admin($data),
            'mentor' => new Mentor($data),
            'student' => new Student($data),
            default => throw new \InvalidArgumentException('Invalid user role')
        };
    }
}