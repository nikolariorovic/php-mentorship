<?php
namespace App\Repositories\Interfaces;

interface AppointmentRepositoryInterface {
    public function getAvailableTimeSlots(int $mentorId, string $date): array;
    public function bookAppointment(int $mentorId, string $dateTime, int $studentId, float $price, int $specializationId): void;
    public function getPaginatedAppointments(int $userId, string $role, int $page): array;
}