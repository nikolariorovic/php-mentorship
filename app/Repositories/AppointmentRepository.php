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

    public function bookAppointment(int $mentorId, string $dateTime, int $studentId, float $price, int $specializationId): void
    {
        $sql = "INSERT INTO appointments (mentor_id, period, student_id, price, created_at, specialization_id) VALUES (?, ?, ?, ?, NOW(), ?)";
        $this->execute($sql, [$mentorId, $dateTime, $studentId, $price, $specializationId]);
    }

    public function getPaginatedAppointments(int $userId, string $role, int $page): array
    {
        if ($role === 'student') {
            $sql = "SELECT a.*, 
                           u.first_name as mentor_name, 
                           u.last_name as mentor_last_name,
                           s.name as specialization_name
                    FROM appointments a
                    JOIN users u ON a.mentor_id = u.id
                    JOIN specializations s ON a.specialization_id = s.id
                    WHERE a.student_id = ? AND a.status != 'rejected' 
                    ORDER BY a.created_at DESC LIMIT 10 OFFSET ?";
        } else {
            $sql = "SELECT a.*, 
                           u.first_name as student_name, 
                           u.last_name as student_last_name,
                           s.name as specialization_name,
                           p.status as payment_status
                    FROM appointments a
                    JOIN users u ON a.student_id = u.id
                    JOIN specializations s ON a.specialization_id = s.id
                    LEFT JOIN payments p ON a.id = p.appointment_id AND p.status = 'confirmed'
                    WHERE a.mentor_id = ? AND a.status != 'rejected' 
                    ORDER BY a.created_at DESC LIMIT 10 OFFSET ?";
        }
        return $this->query($sql, [$userId, ($page - 1) * 10]);
    }

    public function updateAppointmentStatus(int $appointmentId, string $status): void
    {
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $this->execute($sql, [$status, $appointmentId]);
    }

    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void
    {
        $sql = "UPDATE appointments SET status = ?, payment_status = ? WHERE id = ?";
        $this->execute($sql, [$paymentStatus, $isPaid, $appointmentId]);
    }
}