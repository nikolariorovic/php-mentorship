<?php
namespace App\Services\Interfaces;

interface PaymentHistoryServiceInterface
{
    public function getPayments(int $page): array;
    public function paymentsAccepted(int $id): void;
}