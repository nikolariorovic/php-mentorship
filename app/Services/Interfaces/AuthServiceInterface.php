<?php
namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function attempt(string $email, string $password): ?User;
    public function login(User $user): void;
    public function logout(): void;
} 