<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\UserNotFoundException;
use App\Factories\UserFactory;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    public function findByEmail(string $email): ?User {
        try {
            $user = $this->queryOne('SELECT * FROM users WHERE email = ?', [$email]); 
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }

        if (!$user) throw new UserNotFoundException();
        return UserFactory::create($user);
    }

    public function getAllUsers(int $page): array {
        try {
            $users = $this->query('SELECT * FROM users LIMIT 10 OFFSET ?', [($page - 1) * 10]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        } 
        return array_map(fn($userData) => UserFactory::create($userData), $users);
    }

    public function createUser(User $user): void {
        try {
            $this->execute('INSERT INTO users (first_name, last_name, email, password, biography, price, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getBiography(),
                $user->getPrice() ?? 0.00,
                $user->getRole(),
                date('Y-m-d H:i:s')
            ]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }
}