<?php
namespace App\Services\Interfaces;

use App\Models\User;

interface UserReadServiceInterface
{
    public function getPaginatedUsers(int $page): array;
    public function getUserById(int $id): ?User;
    public function getMentorsBySpecialization(int $specializationId): array;
}