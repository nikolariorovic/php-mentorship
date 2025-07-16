<?php
namespace App\Repositories\Interfaces;

interface UserSpecializationRepositoryInterface
{
    public function saveUserSpecializations(int $userId, array $specializationIds): void;
    public function deleteUserSpecializations(int $userId): void;
    public function getUserSpecializations(int $id): array;
}