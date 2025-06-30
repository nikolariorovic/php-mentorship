<?php
namespace App\Middleware;

class MentorMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mentor') {
            header('Location: /');
            return false;
        }
    }
}