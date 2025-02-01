<?php
class Database {
    private $conn;

    public function __construct() {
        $configPath = __DIR__ . '/../config/database.php';
        
        if (!file_exists($configPath)) {
            throw new Exception("Database configuration file not found");
        }

        $config = require $configPath;
        
        if (!is_array($config)) {
            throw new Exception("Invalid database configuration format");
        }

        $host = $config['host'] ?? null;
        $dbname = $config['dbname'] ?? null;
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;
        $port = $config['port'] ?? 3306;
        $charset = $config['charset'] ?? 'utf8mb4';

        if (!$host || !$dbname || !$username) {
            throw new Exception("Missing required database configuration parameters");
        }

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, $username, $password, $options);
        } catch(PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}