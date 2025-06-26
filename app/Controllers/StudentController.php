<?php

namespace App\Controllers;

use App\Services\Interfaces\SpecializationServiceInterface;
use App\Exceptions\DatabaseException;
use App\Services\Interfaces\UserReadServiceInterface;

class StudentController extends Controller {

    public function __construct(SpecializationServiceInterface $specializationService, UserReadServiceInterface $userReadService) {
        $this->specializationService = $specializationService;
        $this->userReadService = $userReadService;
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
            return $this->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
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
            $mentorId = $_GET['mentor_id'];
            $date = $_GET['date'];
            if (!$mentorId || !$date) {
                return $this->json([
                    'success' => false,
                    'message' => 'Mentor ID and date are required'
                ]);
            }
            $slots = $this->userReadService->getAvailableTimeSlots($mentorId, $date);
            return $this->json([
                'success' => true,
                'slots' => $slots
            ]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->json([
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
}
}