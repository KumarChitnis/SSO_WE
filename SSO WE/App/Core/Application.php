<?php
namespace App\Core;

use Dotenv\Dotenv;
use App\Database\Database;

class Application
{
    private static $instance;
    protected $config = [];
    protected $db;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->loadConfig();
    }

    public function loadEnv()
    {
        $envFilePath = __DIR__ . '/../../.env';
        if (!file_exists($envFilePath)) {
            throw new RuntimeException('The .env file does not exist at the specified path: ' . $envFilePath);
        }
        echo "Loading environment variables from: " . $envFilePath . "\n";
        try {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
            echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
            echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'not set') . "\n";
            echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'not set') . "\n";
            echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'not set') . "\n";
        } catch (\Exception $e) {
            echo "Error loading environment variables: " . $e->getMessage() . "\n";
        }
    }

    protected function loadConfig()
    {
        $this->config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'SSO Webtool',
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
                'key' => $_ENV['APP_KEY'] ?? ''
            ],
            'db' => [
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'name' => $_ENV['DB_DATABASE'] ?? 'sso_webtool',
                'user' => $_ENV['DB_USERNAME'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? ''
            ]
        ];
    }

    public function initDatabase()
    {
        $this->db = new Database($this->config['db']);
    }

    public function getDatabase()
    {
        return $this->db;
    }

    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }
        return $this->config;
    }

    public function run()
    {
        // Start session
        session_start([
            'cookie_secure' => $_ENV['SESSION_SECURE_COOKIE'] ?? true,
            'cookie_httponly' => $_ENV['SESSION_HTTP_ONLY'] ?? true,
            'cookie_samesite' => $_ENV['SESSION_SAME_SITE'] ?? 'Strict'
        ]);

        // Get the router from routes/web.php
        $router = require __DIR__ . '/../../routes/web.php';
        
        // Dispatch the router
        $router->dispatch();
    }
}
