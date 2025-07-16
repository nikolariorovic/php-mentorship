<?php
namespace App\Repositories\Interfaces;

interface UserReadRepositoryInterface
{
    public function findByEmail(string $email): ?array;
    public function getAllUsers(int $page): array;
    public function getUserById(int $id): ?array;
    public function getUserByIdOnly(int $id): ?array;
    public function getMentorsBySpecialization(int $specializationId): array;
}