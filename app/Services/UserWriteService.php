<?php
namespace App\Services;

use App\Repositories\Interfaces\UserWriteRepositoryInterface;
use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;
use App\Validators\Interfaces\ValidatorInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Factories\UserFactory;
use App\Models\Mentor;
use App\Helpers\UserHelper;
use App\Exceptions\UserNotFoundException;

class UserWriteService implements UserWriteServiceInterface
{
    public function __construct(UserWriteRepositoryInterface $userWriteRepository, UserReadRepositoryInterface $userReadRepository, UserSpecializationRepositoryInterface $userSpecializationRepository, ValidatorInterface $userCreateValidator, ValidatorInterface $userUpdateValidator)
    {
        $this->userWriteRepository = $userWriteRepository;
        $this->userReadRepository = $userReadRepository;
        $this->userSpecializationRepository = $userSpecializationRepository;
        $this->userCreateValidator = $userCreateValidator;
        $this->userUpdateValidator = $userUpdateValidator;
    }

    public function createUser(array $data): void
    {
        $this->userCreateValidator->validate($data);
        $user = UserFactory::create($data);
        
        $this->userWriteRepository->createUser([
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getBiography(),
            $user->getPrice() ?? 0.00,
            $user->getRole(),
            date('Y-m-d H:i:s')
        ]);

        if ($user instanceof Mentor && isset($data['specializations']) && is_array($data['specializations'])) {
            $userData = $this->userReadRepository->findByEmail($user->getEmail());
            if ($userData) {
                $userId = $userData['id'];
                $specializationIds = array_map('intval', $data['specializations']);
                $this->userSpecializationRepository->saveUserSpecializations($userId, $specializationIds);
            }
        }
    }

    public function updateUser(int $id, array $data): void
    {
        $this->userUpdateValidator->validate($data);

        $userData = $this->userReadRepository->getUserByIdOnly($id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create($userData);
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
            $this->userWriteRepository->updateUser([
                $user->getFirstName(),
                $user->getLastName(),
                $user->getRole(),
                $user->getBiography(),
                $user->getPrice(),
                date('Y-m-d H:i:s'),
                $user->getId()
            ]);
        }

        if ($user instanceof Mentor) {
            if (isset($data['specializations']) && is_array($data['specializations'])) {
                $specializationIds = array_map('intval', $data['specializations']);
                
                $specializationsData = $this->userSpecializationRepository->getUserSpecializations($id);
                $specializations = UserHelper::setSpecializations($specializationsData);
                $user->setSpecializations($specializations);

                $currentSpecializationIds = array_map(fn($s) => $s->getId(), $user->getSpecializations());
                
                $specializationsChanged = count($specializationIds) !== count($currentSpecializationIds) || 
                                        array_diff($specializationIds, $currentSpecializationIds) !== [] ||
                                        array_diff($currentSpecializationIds, $specializationIds) !== [];
                
                if ($specializationsChanged) {
                    $this->userSpecializationRepository->deleteUserSpecializations($user->getId());
                    $this->userSpecializationRepository->saveUserSpecializations($user->getId(), $specializationIds);
                }
            } else {
                $this->userSpecializationRepository->deleteUserSpecializations($user->getId());
            }
        }
    }

    public function deleteUser(int $id): void
    {
        $userData = $this->userReadRepository->getUserByIdOnly($id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create($userData);
        
        if ($user instanceof Mentor) {
            $this->userSpecializationRepository->deleteUserSpecializations($user->getId());
        }
        
        $this->userWriteRepository->deleteUser([
            date('Y-m-d H:i:s'),
            $user->getEmail() . '_deleted_' . time(),
            $user->getId()
        ]);
    }
}
