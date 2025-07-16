<?php
namespace App\Services\Interfaces;

interface AppointmentReadServiceInterface {
    public function getAvailableTimeSlots(array $data): array;
    public function getPaginatedAppointments(int $page): array;
    public function getAppointmentsForDashboard(): array;
}