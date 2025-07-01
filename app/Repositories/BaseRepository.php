<?php
namespace App\Repositories;

use Database\Database;
use App\Exceptions\DatabaseException;
use PDOException;
use PDO;

class BaseRepository {
    protected PDO $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    protected function query(string $sql, array $params = []): array {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }  
    }

    protected function execute(string $sql, array $params = []): bool {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    protected function queryOne(string $sql, array $params = []): ?array {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    protected function handleDatabaseError(PDOException $e): void {
        logError('Database error: ' . $e->getMessage());
        throw new DatabaseException("Database error: " . $e->getMessage());
    }

    protected function getLastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}