<?php
namespace App\Services\Interfaces;

interface AppointmentWriteServiceInterface {
    public function bookAppointment(array $data): void;
    public function updateAppointmentStatus(array $data): void;
    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void;
    public function submitRating(array $data): void;
}