<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Factories\UserFactory;
use App\Validators\Interfaces\ValidatorInterface;
use App\Models\User;
use App\Exceptions\UserNotFoundException;

class UserService implements UserReadServiceInterface, UserWriteServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $userCreateValidator;
    private ValidatorInterface $userUpdateValidator;

    public function __construct(UserRepositoryInterface $userRepository, ValidatorInterface $userCreateValidator, ValidatorInterface $userUpdateValidator)
    {
        $this->userRepository = $userRepository;
        $this->userCreateValidator = $userCreateValidator;
        $this->userUpdateValidator = $userUpdateValidator;
    }

    public function getPaginatedUsers(int $page): array
    {
        return $this->userRepository->getAllUsers($page);
    }

    public function createUser(array $data): void
    {
        $this->userCreateValidator->validate($data);
        $user = UserFactory::create($data);
        $this->userRepository->createUser([
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getBiography(),
            $user->getPrice() ?? 0.00,
            $user->getRole(),
            date('Y-m-d H:i:s')
        ]);
    }

    public function getUserById(int $id): ?User
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) throw new UserNotFoundException();
        return $user;
    }

    public function updateUser(int $id, array $data): void
    {
        $this->userUpdateValidator->validate($data);

        $user = $this->userRepository->getUserById($id);
        if (!$user) throw new UserNotFoundException();
        
        $isModified = false;

        if (isset($data['first_name']) && $user->getFirstName() !== $data['first_name']) {
            $user->setFirstName($data['first_name']);
            $isModified = true;
        }
        if (isset($data['last_name']) && $user->getLastName() !== $data['last_name']) {
            $user->setLastName($data['last_name']);
            $isModified = true;
        }
        if (isset($data['role']) && $user->getRole() !== $data['role']) {
            $user->setRole($data['role']);
            $isModified = true;
        }
        if (array_key_exists('biography', $data) && $user->getBiography() !== $data['biography']) {
            $user->setBiography($data['biography']);
            $isModified = true;
        }
        
        $newPrice = ($data['price'] !== '' && $data['price'] !== null) ? (float)$data['price'] : 0.00;
        if (array_key_exists('price', $data) && $user->getPrice() !== $newPrice) {
            $user->setPrice($newPrice);
            $isModified = true;
        }

        if ($isModified) {
            $this->userRepository->updateUser([
                $user->getFirstName(),
                $user->getLastName(),
                $user->getRole(),
                $user->getBiography(),
                $user->getPrice(),
                date('Y-m-d H:i:s'),
                $user->getId()
            ]);
        }
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) throw new UserNotFoundException();
        $this->userRepository->deleteUser([
            date('Y-m-d H:i:s'),
            $user->getEmail() . '_deleted_' . time(),
            $user->getId()
        ]);
    }
}