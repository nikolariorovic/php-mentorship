<?php
namespace App\Factories\Interfaces;

use App\Models\Specialization;

interface SpecializationFactoryInterface
{
    public static function create(array $data): Specialization;
}