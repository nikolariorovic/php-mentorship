<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;

$router->get('/', [HomeController::class, 'index']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);