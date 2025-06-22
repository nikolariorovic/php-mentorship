<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Factories\UserFactory;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    public function findByEmail(string $email): ?User {
        try {
            $user = $this->queryOne('SELECT * FROM users WHERE email = ? and deleted_at is null', [$email]); 
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }

        return $user ? UserFactory::create($user) : null;
    }

    public function getAllUsers(int $page): array {
        try {
            $users = $this->query('SELECT * FROM users WHERE deleted_at is null LIMIT 10 OFFSET ?', [($page - 1) * 10]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        } 
        return array_map(fn($userData) => UserFactory::create($userData), $users);
    }

    public function createUser(array $params): void {
        try {
            $this->execute('INSERT INTO users (first_name, last_name, email, password, biography, price, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    public function getUserById(int $id): ?User
    {
        try {
            $user = $this->queryOne('SELECT * FROM users WHERE id = ? and deleted_at is null', [$id]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }

        return $user ? UserFactory::create($user) : null;
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
}