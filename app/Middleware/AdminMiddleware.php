<?php
namespace App\Middleware;

class AdminMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'mentor') {
            header('Location: /');
            return false;
        }
        return true;
    }
} 