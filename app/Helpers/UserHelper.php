<?php
namespace App\Helpers;

use App\Models\Specialization;

class UserHelper
{
    public static function setSpecializations(array $userSqlData): array
    {
        $specializations = [];
        foreach ($userSqlData as $row) {
            if (!empty($row['specialization_id'])) {
                $specializations[$row['specialization_id']] = new Specialization(
                    $row['specialization_id'],
                    $row['specialization_name'],
                    $row['specialization_description'],
                    $row['specialization_created_at'],
                    $row['specialization_updated_at']
                );
            }
        }
        return $specializations;
    }

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