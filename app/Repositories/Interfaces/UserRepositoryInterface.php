<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface{
    public function findByEmail(string $email): ?User;
    public function getAllUsers(int $page): array;
    public function createUser(array $params): void;
    public function getUserById(int $id): ?User;
    public function updateUser(array $params): void;
    public function deleteUser(array $params): void;
}