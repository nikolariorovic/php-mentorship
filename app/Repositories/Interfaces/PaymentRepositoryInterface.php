<?php
namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    public function savePayment(array $paymentData): int;
    public function updatePaymentStatus(int $paymentId, string $status): void;
}