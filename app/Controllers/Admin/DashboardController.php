<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\AppointmentReadServiceInterface;

class DashboardController extends Controller
{   
    private AppointmentReadServiceInterface $appointmentReadService;

    public function __construct(AppointmentReadServiceInterface $appointmentReadService)
    {
        $this->appointmentReadService = $appointmentReadService;
    }
    public function index()
    {
        try {
            $totalData = $this->appointmentReadService->getAppointmentsForDashboard();
            return $this->view('admin/dashboard', 
            [
                'appointments' => $totalData['appointments'], 
                'profit' => $totalData['profit'], 
                'mentors' => $totalData['mostActiveAndMostRatedMentors']
            ]);
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->view('admin/dashboard');
        }
    }
}