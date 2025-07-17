<?php
namespace App\Services;

use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;
use App\Factories\UserFactory;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Models\User;
use App\Models\Mentor;
use App\Helpers\UserHelper;
use App\Exceptions\UserNotFoundException;

class UserReadService implements UserReadServiceInterface
{
    private UserReadRepositoryInterface $userReadRepository;
    private UserSpecializationRepositoryInterface $userSpecializationRepository;

    public function __construct(
        UserReadRepositoryInterface $userReadRepository, 
        UserSpecializationRepositoryInterface $userSpecializationRepository
    ) {
        $this->userReadRepository = $userReadRepository;
        $this->userSpecializationRepository = $userSpecializationRepository;
    }

    public function getPaginatedUsers(int $page): array
    {
        $users = $this->userReadRepository->getAllUsers($page);
        return array_map(fn($userData) => UserFactory::create($userData), $users);
    }

    public function getUserById(int $id): ?User
    {
        $userData = $this->userReadRepository->getUserByIdOnly($id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create($userData);

        if ($user instanceof Mentor) {
            $specializationsData = $this->userSpecializationRepository->getUserSpecializations($id);
            $specializations = UserHelper::setSpecializations($specializationsData);
            $user->setSpecializations($specializations);
        }
        
        return $user;
    }

    public function getMentorsBySpecialization(int $specializationId): array
    {
        return $this->userReadRepository->getMentorsBySpecialization($specializationId);
    }
}
