<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use App\Core\Application;

// Initialize application
$app = new Application();

// Load environment variables
$app->loadEnv();

// Debugging output for environment variables
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_USER: " . getenv('DB_USER') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "DB_PASSWORD: " . getenv('DB_PASSWORD') . "\n";

// Verify environment variables are loaded
if (!isset($_ENV['APP_ENV'])) {
    throw new RuntimeException('Environment variables not loaded properly');
}

// Initialize database connection
$app->initDatabase();

// Register routes
require 'routes/web.php';

// Start application
$app->run();