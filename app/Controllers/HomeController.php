<?php

namespace App\Controllers;

class HomeController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            $this->view('login');
            return;
        }

        $role = $_SESSION['user']['role'] ?? null;
        switch ($role) {
            case 'student':
                $this->redirect('/home');
                break;
            case 'mentor':
                $this->view('mentor/index');
                break;
            case 'admin':
                $this->redirect('/admin/users');
                break;
            default:
                $this->view('login');
        }
    }
}