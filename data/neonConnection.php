<?php

class Conexion {
    private $pdo;

    public function __construct() {
        $host = $_ENV['DB_HOST'] ?? '161.132.41.206';
        $db   = $_ENV['DB_NAME'] ?? 'peti';
        $user = $_ENV['DB_USER'] ?? 'oscarjimenez';
        $pass = $_ENV['DB_PASSWORD'] ?? 'oscarjimenez';
        $port = 3306;

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log('Error DSN: ' . $dsn);
            die('❌ Error de conexión: ' . $e->getMessage());
        } 
    }

    public function getConnection() {
        return $this->pdo;
    }
}
