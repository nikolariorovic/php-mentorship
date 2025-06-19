<?php
namespace App\Services\Interfaces;

interface UserWriteServiceInterface
{
    public function createUser(array $data): void;
}