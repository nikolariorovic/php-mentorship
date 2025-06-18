<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface{
    public function findByEmail(string $email): ?User;
    public function getAllUsers($page): array;
}