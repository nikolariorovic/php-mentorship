<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface {
    
    public function getAvailableTimeSlots(int $mentorId, string $date): array
    {
        try {
            $sql = "SELECT period FROM appointments 
                    WHERE mentor_id = ? 
                    AND DATE(period) = ? 
                    AND status != 'rejected'";
                  
            return $this->query($sql, [$mentorId, $date]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function bookAppointment(int $mentorId, string $dateTime, int $studentId, float $price): void
    {
        $sql = "INSERT INTO appointments (mentor_id, period, student_id, price, created_at) VALUES (?, ?, ?, ?, NOW())";
        $this->execute($sql, [$mentorId, $dateTime, $studentId, $price]);
    }
}