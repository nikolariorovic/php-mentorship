<?php
namespace App\Services\Interfaces;

interface AppointmentReadServiceInterface {
    public function getAvailableTimeSlots(array $data): array;
}