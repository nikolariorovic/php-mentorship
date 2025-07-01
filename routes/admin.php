<?php

use App\Controllers\Admin\UserAdminController;
use App\Controllers\Admin\MentorAdminController;
use App\Controllers\PaymentController;
use App\Middleware\AdminMiddleware;
use App\Middleware\MentorMiddleware;

$router->group(['middleware' => [AdminMiddleware::class]], function ($router) {
    //users CRUD
    $router->get('/users', [UserAdminController::class, 'index']);
    $router->post('/users', [UserAdminController::class, 'create']);
    $router->get('/users/{id}', [UserAdminController::class, 'show']);
    $router->patch('/users/{id}', [UserAdminController::class, 'update']);
    $router->delete('/users/{id}', [UserAdminController::class, 'delete']);
    $router->get('/payments', [PaymentController::class, 'getPayments']);
    $router->post('/paymentsAccepted/{id}', [PaymentController::class, 'paymentsAccepted']);
});

$router->group(['middleware' => [MentorMiddleware::class]], function ($router) {
    $router->get('/mentor', [MentorAdminController::class, 'index']);
    $router->post('/mentor/update-status-appointment', [MentorAdminController::class, 'updateAppointmentStatus']);
    $router->get('/mentor/{id}', [MentorAdminController::class, 'show']);
    $router->patch('/mentor/{id}', [MentorAdminController::class, 'update']);
    $router->delete('/mentor/{id}', [MentorAdminController::class, 'delete']);
});