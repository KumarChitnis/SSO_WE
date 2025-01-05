<?php
namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private $connection;
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    private function connect()
    {
        try {
            echo "Attempting to connect to database...\n";
            echo "Host: {$this->config['host']}\n";
            echo "Database: {$this->config['name']}\n";
            echo "User: {$this->config['user']}\n";
            
            $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['name']};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO(
                $dsn,
                $this->config['user'],
                $this->config['password'],
                $options
            );
            
            echo "Database connection successful!\n";
        } catch (PDOException $e) {
            echo "Database connection error: " . $e->getMessage() . "\n";
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        echo "Executing query: $sql with parameters: " . json_encode($params) . "\n";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
