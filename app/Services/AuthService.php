<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function attempt(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && $user->verifyPassword($password)) {
            return $user;
        }
        return null;
    }

    public function login(User $user): void
    {
        $_SESSION['user'] = $user->toArray();
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
} 