<?php

use App\Controllers\Admin\AdminController;

$router->get('/users', [AdminController::class, 'index']);
$router->post('/users', [AdminController::class, 'create']);