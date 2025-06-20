<?php
namespace App\Validators\Interfaces;

interface ValidatorInterface
{
    public function validate(array $data): void;
}