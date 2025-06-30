<?php
namespace App\Repositories\Interfaces;

interface AppointmentRepositoryInterface {
    public function getAvailableTimeSlots(int $mentorId, string $date): array;
    public function bookAppointment(int $mentorId, string $dateTime, int $studentId, float $price): void;
}