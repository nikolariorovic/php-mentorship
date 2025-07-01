<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

final class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function savePayment(array $paymentData): int
    {
        $sql = "INSERT INTO payments (
            appointment_id, 
            student_id, 
            price, 
            status, 
            method, 
            transaction_id, 
            card_number, 
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $this->execute($sql, [
            $paymentData['appointment_id'],
            $paymentData['student_id'],
            $paymentData['price'],
            $paymentData['status'],
            $paymentData['method'],
            $paymentData['transaction_id'],
            $paymentData['card_number']
        ]);
        
        return $this->getLastInsertId();
    }
    
    public function updatePaymentStatus(int $paymentId, string $status): void
    {
        $sql = "UPDATE payments SET status = ? WHERE id = ?";
        $this->execute($sql, [$status, $paymentId]);
    }
    
    public function getPayments(int $page): array
    {
        return $this->query('SELECT * FROM payments WHERE status = "pending" ORDER BY created_at DESC LIMIT 10 OFFSET ?', [($page - 1) * 10]);
    }

    public function paymentsAccepted(int $id): void
    {
        $sql = "UPDATE payments SET status = 'confirmed' WHERE id = ?";
        $this->execute($sql, [$id]);
    }
} 