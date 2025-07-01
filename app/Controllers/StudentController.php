<?php

namespace App\Controllers;

use App\Services\Interfaces\SpecializationServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidBookingDataException;
use App\Exceptions\InvalidTimeSlotDataException;
use App\Exceptions\InvalidArgumentException;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;

class StudentController extends Controller {

    public function __construct(SpecializationServiceInterface $specializationService, UserReadServiceInterface $userReadService, AppointmentReadServiceInterface $appointmentReadService, AppointmentWriteServiceInterface $appointmentWriteService) {
        $this->specializationService = $specializationService;
        $this->userReadService = $userReadService;
        $this->appointmentReadService = $appointmentReadService;
        $this->appointmentWriteService = $appointmentWriteService;
    }

    public function index() {
        try {
            $specializations = $this->specializationService->getAllSpecializations();
            return $this->view('student/index', ['specializations' => $specializations]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->view('student/index');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->view('student/index');
        }
    }

    public function getMentorBySpecialization(int $specializationId) {
        try {
            $mentors = $this->userReadService->getMentorsBySpecialization($specializationId);
            return $this->json([
                'success' => true,
                'mentors' => $mentors
            ]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->json(json_decode((string) $e, true));
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->json([
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function getAvailableTimeSlots() {
        try {
            $slots = $this->appointmentReadService->getAvailableTimeSlots($_GET);
            return $this->json([
                'success' => true,
                'slots' => $slots
            ]);
        } catch (InvalidTimeSlotDataException $e) {
            return $this->json(json_decode((string) $e, true));
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->json(json_decode((string) $e, true));
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->json([
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function bookAppointment() {
        try {
            $this->appointmentWriteService->bookAppointment($_POST);
            return $this->json([
                'success' => true,
                'message' => 'Appointment booked successfully'
            ]);
        } catch (InvalidBookingDataException $e) {
            return $this->json(json_decode((string) $e, true));
        } catch (InvalidArgumentException $e) {
            $this->handleException($e, 'User not authenticated. Please login again.');
            return $this->json(json_decode((string) $e, true));
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->json(json_decode((string) $e, true));
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->json([
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function appointments() {
        try {
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;

            $appointments = $this->appointmentReadService->getPaginatedAppointments($page);
            return $this->view('student/appointments', ['appointments' => $appointments]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->view('student/appointments');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->view('student/appointments');
        }
    }
}