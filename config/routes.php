<?php
/** @var Core\Router $router */

// Homepage
$router->get('/', [Controllers\HomeController::class, 'index']);

// Auth routes
$router->get('/login', [Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [Controllers\AuthController::class, 'login']);
$router->get('/logout', [Controllers\AuthController::class, 'logout']);

// TODO: add other domain routes e.g., vehicles, maintenance, etc.
$router->get('/vehicles', [Controllers\VehicleController::class, 'index']);