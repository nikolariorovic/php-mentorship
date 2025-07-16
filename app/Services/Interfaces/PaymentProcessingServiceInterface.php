<?php
namespace App\Services\Interfaces;

use App\Services\PaymentGateway\PaymentGatewayInterface;

interface PaymentProcessingServiceInterface
{
    public function processPayment(string $gatewayName = null, array $data = []): array;
    public function registerGateway(PaymentGatewayInterface $gateway): void;
}