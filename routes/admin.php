<?php

use App\Controllers\Admin\UserAdminController;

$router->get('/users', [UserAdminController::class, 'index']);
$router->post('/users', [UserAdminController::class, 'create']);