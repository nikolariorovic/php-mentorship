<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Factories\UserFactory;
use App\Validators\Interfaces\ValidatorInterface;

class UserService implements UserReadServiceInterface, UserWriteServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $userValidator;

    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
    }

    public function getPaginatedUsers(int $page): array
    {
        return $this->userRepository->getAllUsers($page);
    }

    public function createUser(array $data): void
    {
        $this->userValidator->validate($data);
        $user = UserFactory::create($data);
        $this->userRepository->createUser($user);
    }
}