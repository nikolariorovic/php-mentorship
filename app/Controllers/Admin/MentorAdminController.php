<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidArgumentException;

class MentorAdminController extends Controller
{
    public function __construct(AppointmentReadServiceInterface $appointmentReadService, AppointmentWriteServiceInterface $appointmentWriteService) 
    {
        $this->appointmentReadService = $appointmentReadService;
        $this->appointmentWriteService = $appointmentWriteService;
    }
    public function index()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
        
        try {
            $appointments = $this->appointmentReadService->getPaginatedAppointments($page);
            return $this->view('mentor/index', ['appointments' => $appointments]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->view('mentor/index');
        } catch (InvalidArgumentException $e) {
            $this->handleException($e, 'User not authenticated. Please login again.');
            return $this->view('mentor/index');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->view('mentor/index');
        }
    }
    public function updateAppointmentStatus()
    {
        try {
            $this->appointmentWriteService->updateAppointmentStatus($_POST);
            return $this->json(['success' => true]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->json(['success' => false]);
        } catch (InvalidArgumentException $e) {
            $this->handleException($e, 'User not authenticated. Please login again.');
            return $this->json(['success' => false]);
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->json(['success' => false]);
        }
    }
}
