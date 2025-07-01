<?php
namespace App\Services\Interfaces;

interface PaymentServiceInterface 
{
    public function processPayment(array $data): void;
}