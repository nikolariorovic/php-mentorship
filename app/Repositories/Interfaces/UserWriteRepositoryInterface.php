<?php
namespace App\Repositories\Interfaces;

interface UserWriteRepositoryInterface
{
    public function createUser(array $params): void;
    public function updateUser(array $params): void;
    public function deleteUser(array $params): void;
}