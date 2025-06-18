<?php
namespace App\Services\Interfaces;

interface UserReadServiceInterface
{
    public function getPaginatedUsers($page): array;
}