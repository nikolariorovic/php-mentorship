<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\StudentController;
use App\Middleware\StudentMiddleware;


$router->get('/', [HomeController::class, 'index']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);


$router->group(['middleware' => [StudentMiddleware::class]], function ($router) {
    $router->get('/home', [StudentController::class, 'index']);
    $router->get('/getMentorBySpecialization/{specializationId}', [StudentController::class, 'getMentorBySpecialization']);
    $router->get('/getAvailableTimeSlots', [StudentController::class, 'getAvailableTimeSlots']);
    $router->post('/bookAppointment', [StudentController::class, 'bookAppointment']);
});
