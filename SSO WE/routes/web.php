<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

$router = new Router();

// Home routes
$router->get('/', [HomeController::class, 'index']);

// Authentication routes
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegistrationForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Verification routes
$router->get('/verify/email', [AuthController::class, 'verifyEmail']);
$router->get('/verify/phone', [AuthController::class, 'verifyPhone']);

// Error handling
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo 'Page not found';
});

return $router;
