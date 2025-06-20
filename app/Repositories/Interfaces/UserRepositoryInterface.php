<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface{
    public function findByEmail(string $email): ?User;
    public function getAllUsers(int $page): array;
    public function createUser(User $user): void;
}