<?php
namespace App\Middleware;

class StudentMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: /');
            return false;
        }
    }
}