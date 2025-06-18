<?php

namespace Database;

use PDO;
use PDOException;

class Database {
    private string $host;
    private string $port;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn;

    public function __construct() {
        $envFile = __DIR__ . '/../.env';
        $env = parse_ini_file($envFile);
        
        $this->host = $env['DB_HOST'] ?? 'localhost';
        $this->port = $env['DB_PORT'] ?? '3306';
        $this->db_name = $env['DB_NAME'] ?? '';
        $this->username = $env['DB_USER'] ?? '';
        $this->password = $env['DB_PASS'] ?? '';
    }

    public function getConnection(): ?PDO {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            logError('Database error: ' . $e->getMessage());
        }

        return $this->conn;
    }
} 