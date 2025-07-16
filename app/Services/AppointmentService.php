<?php
namespace App\Services;

use App\Helpers\AppointmentHelper;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Validators\BookingValidator;
use App\Validators\TimeSlotValidator;
use App\Validators\UpdateAppointmentStatusValidator;
use App\Exceptions\InvalidBookingDataException;
use App\Exceptions\InvalidTimeSlotDataException;
use App\Exceptions\InvalidArgumentException;
use App\Validators\RatingValidator;

class AppointmentService implements AppointmentReadServiceInterface, AppointmentWriteServiceInterface 
{
    public function __construct(AppointmentRepositoryInterface $appointmentRepository, BookingValidator $bookingValidator, TimeSlotValidator $timeSlotValidator, UpdateAppointmentStatusValidator $updateAppointmentStatusValidator, RatingValidator $ratingValidator) {
        $this->appointmentRepository = $appointmentRepository;
        $this->bookingValidator = $bookingValidator;
        $this->timeSlotValidator = $timeSlotValidator;
        $this->updateAppointmentStatusValidator = $updateAppointmentStatusValidator;
        $this->ratingValidator = $ratingValidator;
    }

    // public function getAvailableTimeSlots(array $data): array
    // {
    //     $this->timeSlotValidator->validate($data);
        
    //     $mentorId = (int) $data['mentor_id'];
    //     $date = $data['date'];
        
    //     $bookedSlots = $this->appointmentRepository->getAvailableTimeSlots($mentorId, $date);
    //     $allSlots = AppointmentHelper::getAllTimeSlotsForDate($date);
        
    //     if (empty($bookedSlots)) {
    //         return $allSlots;
    //     }
        
    //     $bookedTimes = array_map(function($slot) {
    //         return $slot['period'];
    //     }, $bookedSlots);
        
    //     $availableSlots = array_filter($allSlots, function($slot) use ($bookedTimes) {
    //         return !in_array($slot['time'], $bookedTimes);
    //     });
        
    //     return array_values($availableSlots);
    // }

    // public function bookAppointment(array $data): void
    // {
    //     if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    //         throw new \InvalidArgumentException('User not authenticated. Please login again.');
    //     }

    //     $this->bookingValidator->validate($data);
        
    //     $dateTime = strpos($data['time'], ' ') !== false ? $data['time'] : $data['date'] . ' ' . $data['time'];
        
    //     $this->appointmentRepository->bookAppointment($data['mentor_id'], $dateTime, $_SESSION['user']['id'], $data['price'], $data['specialization_id']);
    // }

    // public function getPaginatedAppointments(int $page): array
    // {
    //     if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    //         throw new InvalidArgumentException('User not authenticated. Please login again.');
    //     }
     
    //     return $this->appointmentRepository->getPaginatedAppointments($_SESSION['user']['id'], $_SESSION['user']['role'], $page);
    // }

    // public function updateAppointmentStatus(array $data): void
    // {
    //     $this->updateAppointmentStatusValidator->validate($data);
    //     $this->appointmentRepository->updateAppointmentStatus($data['appointment_id'], $data['status']);
    // }

    // public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void
    // {
    //     $this->appointmentRepository->updatePaymentStatus($appointmentId, $paymentStatus, $isPaid);
    // }

    // public function submitRating(array $data): void
    // {
    //     $this->ratingValidator->validate($data);
    //     $this->appointmentRepository->submitRating($data['appointment_id'], $data['rating'], $data['comment']);
    // }

    // public function getAppointmentsForDashboard(): array
    // {
    //     $getAppointmentsForDashboard = $this->appointmentRepository->getAppointmentsForDashboard();
    //     $getSumOfProfit = $this->appointmentRepository->getSumOfProfit();
    //     $getMostActiveAndMostRatedMentors = $this->appointmentRepository->getMostActiveAndMostRatedMentors();
    //     return [
    //         'appointments' => $getAppointmentsForDashboard,
    //         'profit' => $getSumOfProfit,
    //         'mostActiveAndMostRatedMentors' => $getMostActiveAndMostRatedMentors
    //     ];
    // }
}