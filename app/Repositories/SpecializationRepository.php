<?php
namespace App\Repositories;

use App\Repositories\Interfaces\SpecializationRepositoryInterface;

class SpecializationRepository extends BaseRepository implements SpecializationRepositoryInterface
{
    public function getAll(): array
    {
        try {
            return $this->query('SELECT * FROM specializations ORDER BY name ASC');
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }
} 