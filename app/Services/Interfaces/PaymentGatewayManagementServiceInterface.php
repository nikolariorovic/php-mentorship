<?php
namespace App\Services\Interfaces;

interface PaymentGatewayManagementServiceInterface
{
    public function isGatewayAvailable(string $gatewayName): bool;
    public function getAvailableGateways(): array;
}