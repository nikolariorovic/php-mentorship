<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Exceptions\UserNotFoundException;
use App\Factories\UserFactory;

class AuthService implements AuthServiceInterface
{
    public function __construct(UserReadRepositoryInterface $userReadRepository)
    {
        $this->userReadRepository = $userReadRepository;
    }
    
    public function attempt(string $email, string $password): ?User
    {
        $user = $this->userReadRepository->findByEmail($email);
        $user = $user ? UserFactory::create($user) : null;
        if ($user && $user->verifyPassword($password)) {
            return $user;
        }
        throw new UserNotFoundException();
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