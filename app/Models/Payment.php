<?php

namespace App\Models;

use DateTime;
use App\Exceptions\InvalidArgumentException;

class Payment
{
    protected int $appointment_id = 0;
    protected int $student_id = 0;
    protected float $price = 0.0;
    protected string $status = 'pending';
    protected string $method = '';
    protected string $transaction_id = '';
    protected string $card_number = '';
    protected DateTime $created_at;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->setAppointmentId($data['appointment_id'] ?? 0);
            $this->setStudentId($data['student_id'] ?? 0);
            $this->setPrice($data['price'] ?? 0.0);
            $this->setStatus($data['status'] ?? 'pending');
            $this->setMethod($data['method'] ?? '');
            $this->setTransactionId($data['transaction_id'] ?? '');
            $this->setCardNumber($data['card_number'] ?? '');
            $this->created_at = isset($data['created_at']) 
                ? new DateTime($data['created_at']) 
                : new DateTime();
        }
    }

    public function getAppointmentId(): int
    {
        return $this->appointment_id;
    }

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setAppointmentId(int $appointmentId): void
    {
        if ($appointmentId <= 0) {
            throw new InvalidArgumentException('Appointment ID must be positive');
        }
        $this->appointment_id = $appointmentId;
    }

    public function setStudentId(int $studentId): void
    {
        if ($studentId <= 0) {
            throw new InvalidArgumentException('Student ID must be positive');
        }
        $this->student_id = $studentId;
    }

    public function setPrice(float $price): void
    {
        if ($price <= 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }
        $this->price = $price;
    }

    public function setStatus(string $status): void
    {
        $allowedStatuses = ['pending', 'confirmed','failed'];
        if (!in_array($status, $allowedStatuses)) {
            throw new InvalidArgumentException('Invalid status. Must be one of: ' . implode(', ', $allowedStatuses));
        }
        $this->status = $status;
    }

    public function setMethod(string $method): void
    {
        if (empty(trim($method))) {
            throw new InvalidArgumentException('Invalid payment method.');
        }
        $this->method = $method;
    }

    public function setTransactionId(string $transactionId): void
    {
        if (empty(trim($transactionId))) {
            throw new InvalidArgumentException('Transaction ID cannot be empty');
        }
        $this->transaction_id = $transactionId;
    }

    public function setCardNumber(string $cardNumber): void
    {
        if (empty(trim($cardNumber))) {
            throw new InvalidArgumentException('Card number cannot be empty');
        }
        $this->card_number = $cardNumber;
    }

    public function toArray(): array
    {
        return [
            'appointment_id' => $this->appointment_id,
            'student_id' => $this->student_id,
            'price' => $this->price,
            'status' => $this->status,
            'method' => $this->method,
            'transaction_id' => $this->transaction_id,
            'card_number' => $this->card_number,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}