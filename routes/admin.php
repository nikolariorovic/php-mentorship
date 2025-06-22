<?php

use App\Controllers\Admin\UserAdminController;
use App\Middleware\AdminMiddleware;

$router->group(['middleware' => [AdminMiddleware::class]], function ($router) {
    //users CRUD
    $router->get('/users', [UserAdminController::class, 'index']);
    $router->post('/users', [UserAdminController::class, 'create']);
    $router->get('/users/{id}', [UserAdminController::class, 'show']);
    $router->patch('/users/{id}', [UserAdminController::class, 'update']);
    $router->delete('/users/{id}', [UserAdminController::class, 'delete']);
});