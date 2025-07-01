<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway;

final class FakePaymentGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {
        usleep(500000);
        
        if (rand(1, 100) <= 99) {
            $transactionId = 'FAKE_' . time() . '_' . rand(1000, 9999);
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'message' => 'Payment processed successfully',
                'status' => 'pending',
                'amount' => $data['amount'] ?? 0,
                'gateway' => $this->getName()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Payment declined by bank',
                'transaction_id' => null,
                'status' => 'failed',
                'gateway' => $this->getName()
            ];
        }
    }

    public function getName(): string
    {
        return 'fake';
    }

    public function getDisplayName(): string
    {
        return 'Fake Payment Gateway';
    }
} 