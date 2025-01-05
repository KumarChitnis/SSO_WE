<?php
// Define application constants
define('APP_ROOT', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: 'production');

// Load environment variables
if (file_exists(APP_ROOT . '/.env')) {
    echo "Found .env file\n";
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();
        echo "Environment variables loaded successfully\n";
    } catch (Exception $e) {
        echo "Error loading environment variables: " . $e->getMessage() . "\n";
    }
} else {
    echo "Could not find .env file\n";
}

// Register the autoloader
spl_autoload_register(function ($class) {
    $file = APP_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Set error reporting based on environment
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load helper functions
require APP_ROOT . '/helpers.php';
