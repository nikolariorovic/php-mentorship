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

    public function getPaginatedAppointments(int $userId, int $page): array
    {
        $sql = "SELECT * FROM appointments WHERE mentor_id = ? AND status != 'rejected' ORDER BY created_at DESC LIMIT 10 OFFSET ?";
        return $this->query($sql, [$userId, ($page - 1) * 10]);
    }

    public function updateAppointmentStatus(int $appointmentId, string $status): void
    {
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $this->execute($sql, [$status, $appointmentId]);
    }
}