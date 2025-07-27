<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Config;
use App\Core\Request;
use App\Core\Router;

// --------------------------------------------------
// Bootstrap application
// --------------------------------------------------
Config::loadEnv(dirname(__DIR__));

$router = new Router();

// Register routes
$router->get('/', [\App\Controllers\DashboardController::class, 'index']);

// Handle request
$response = $router->dispatch(Request::capture());
$response->send();