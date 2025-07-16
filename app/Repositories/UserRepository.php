<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserWriteRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;

class UserRepository extends BaseRepository implements UserReadRepositoryInterface, UserWriteRepositoryInterface, UserSpecializationRepositoryInterface {

    public function findByEmail(string $email): ?array{
        try {
            return $this->queryOne('SELECT * FROM users WHERE email = ? and deleted_at is null', [$email]); 
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function getUserById(int $id): ?array
    {
        return $this->getUserBy('id', $id);
    }

    public function getUserByIdOnly(int $id): ?array
    {
        try {
            return $this->queryOne('SELECT * FROM users WHERE id = ? AND deleted_at IS NULL', [$id]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function getUserSpecializations(int $id): array
    {
        try {
            $sql = "SELECT 
                        s.id as specialization_id, 
                        s.name as specialization_name,
                        s.description as specialization_description,
                        s.created_at as specialization_created_at,
                        s.updated_at as specialization_updated_at
                    FROM users u
                    LEFT JOIN user_specializations us ON u.id = us.user_id
                    LEFT JOIN specializations s ON us.specialization_id = s.id
                    WHERE u.id = ? AND u.deleted_at IS NULL";
            
            return $this->query($sql, [$id]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    private function getUserBy(string $column, $value): ?array
    {
        $sql = "SELECT 
                    u.*, 
                    s.id as specialization_id, 
                    s.name as specialization_name,
                    s.description as specialization_description,
                    s.created_at as specialization_created_at,
                    s.updated_at as specialization_updated_at
                FROM users u
                LEFT JOIN user_specializations us ON u.id = us.user_id
                LEFT JOIN specializations s ON us.specialization_id = s.id
                WHERE u.{$column} = ? AND u.deleted_at IS NULL";

        try {
            return $this->query($sql, [$value]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function getAllUsers(int $page): array {
        try {
            return $this->query('SELECT * FROM users WHERE deleted_at is null LIMIT 10 OFFSET ?', [($page - 1) * 10]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function createUser(array $params): void {
        try {
            $this->execute('INSERT INTO users (first_name, last_name, email, password, biography, price, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function updateUser(array $params): void
    {
        try {
            $this->execute("UPDATE users SET first_name = ?, last_name = ?, role = ?, biography = ?, price = ?, updated_at = ? WHERE id = ?", $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function deleteUser(array $params): void
    {
        try {
            $this->execute('UPDATE users SET deleted_at = ?, email = ? WHERE id = ?', $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function saveUserSpecializations(int $userId, array $specializationIds): void
    {
        try {
            $placeholders = str_repeat('(?, ?), ', count($specializationIds) - 1) . '(?, ?)';
            $params = [];
            foreach ($specializationIds as $specializationId) {
                $params[] = $userId;
                $params[] = $specializationId;
            }
            
            $sql = "INSERT INTO user_specializations (user_id, specialization_id) VALUES {$placeholders}";
            $this->execute($sql, $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function deleteUserSpecializations(int $userId): void
    {
        try {
            $this->execute('DELETE FROM user_specializations WHERE user_id = ?', [$userId]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function getMentorsBySpecialization(int $specializationId): array
    {
        try {
            $sql = "SELECT DISTINCT u.id, u.first_name, u.last_name, u.biography, u.price
                    FROM users u
                    INNER JOIN user_specializations us ON u.id = us.user_id
                    WHERE us.specialization_id = ? AND u.role = 'mentor' AND u.deleted_at IS NULL
                    ORDER BY u.first_name, u.last_name";
            
            return $this->query($sql, [$specializationId]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }
}