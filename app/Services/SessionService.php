<?php
namespace App\Services;

use App\Services\Interfaces\SessionServiceInterface;

class SessionService implements SessionServiceInterface
{
    public function getSession(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}