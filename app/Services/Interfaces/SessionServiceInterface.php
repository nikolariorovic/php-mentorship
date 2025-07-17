<?php
namespace App\Services\Interfaces;

interface SessionServiceInterface
{
    public function getSession(): ?array;
}