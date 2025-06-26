<?php

namespace App\Services;

use App\Repositories\Interfaces\SpecializationRepositoryInterface;
use App\Factories\SpecializationFactory;
use App\Services\Interfaces\SpecializationServiceInterface;

class SpecializationService implements SpecializationServiceInterface
{
    public function __construct(SpecializationRepositoryInterface $specializationRepository)
    {
        $this->specializationRepository = $specializationRepository;
    }

    public function getAllSpecializations(): array
    {
        $specializations = $this->specializationRepository->getAll();
        return array_map(fn($specialization) => SpecializationFactory::create($specialization), $specializations);
    }
}