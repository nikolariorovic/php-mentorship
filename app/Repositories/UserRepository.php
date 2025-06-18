<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Admin;
use App\Models\Mentor;
use App\Models\Student;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\UserNotFoundException;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    private function createUserFromData(array $data): User {
        return match($data['role']) {
            'admin' => new Admin($data),
            'mentor' => new Mentor($data),
            'student' => new Student($data),
            default => throw new \InvalidArgumentException('Invalid user role')
        };
    }

    public function findByEmail(string $email): ?User {
        try {
            $user = $this->queryOne('SELECT * FROM users WHERE email = ?', [$email]); 
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }

        if (!$user) throw new UserNotFoundException();
        return $this->createUserFromData($user);   
    }

    public function getAllUsers($page): array {
        try {
            $users = $this->query('SELECT * FROM users LIMIT 10 OFFSET ?', [($page - 1) * 10]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        } 
        return array_map([$this, 'createUserFromData'], $users);
    }
}