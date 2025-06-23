<?php
namespace App\Factories;

use App\Factories\Interfaces\SpecializationFactoryInterface;
use App\Models\Specialization;

class SpecializationFactory implements SpecializationFactoryInterface
{
    public static function create(array $data): Specialization 
    {
        return new Specialization(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['created_at'],
            $data['updated_at']
        );
    }
}