<?php
namespace App\Middleware;

class AdminMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            return false;
        }
    }
}