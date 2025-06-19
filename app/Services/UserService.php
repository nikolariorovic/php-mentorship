<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Validators\UserValidator;
use App\Exceptions\InvalidUserDataException;
use App\Factories\UserFactory;

class UserService implements UserReadServiceInterface, UserWriteServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private UserValidator $userValidator;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = new UserValidator();
    }

    public function getPaginatedUsers($page): array
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