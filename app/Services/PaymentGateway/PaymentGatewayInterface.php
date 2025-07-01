<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway;

interface PaymentGatewayInterface
{
    public function charge(array $data): array;

    public function getName(): string;

    public function getDisplayName(): string;
} 