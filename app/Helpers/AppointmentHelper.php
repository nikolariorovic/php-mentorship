<?php
namespace App\Helpers;

class AppointmentHelper {
    public static function getAllTimeSlotsForDate(string $date): array
    {
        $allSlots = [];
        $currentDate = date('Y-m-d');
        $currentHour = date('H');
        if ($currentDate == $date && $currentHour >= 20) {
            return $allSlots;
        }
        if ($currentDate != $date) {
            $currentHour = 9;
        }
        for ($hour = $currentHour; $hour <= 20; $hour++) {
            $timeSlot = sprintf('%s %02d:00:00', $date, $hour);
            $displayTime = sprintf('%02d:00', $hour);
            $allSlots[] = [
                'time' => $timeSlot,
                'display_time' => $displayTime
            ];
        }
        return $allSlots;
    }
}