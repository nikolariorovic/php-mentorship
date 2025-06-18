<?php

namespace App\Validators;

use App\Exceptions\InvalidUserDataException;

class UserValidator
{
    private array $errors = [];

    public function validate(array $data): void
    {
        $this->validateFirstName($data['first_name'] ?? '');
        $this->validateLastName($data['last_name'] ?? '');
        $this->validateEmail($data['email'] ?? '');
        // $this->validatePassword($data['password'] ?? '');
        $this->validateRole($data['role'] ?? '');
        $this->validateBiography($data['biography'] ?? '');

        if (!empty($this->errors)) {
            $exception = (new InvalidUserDataException())
                ->setErrors($this->errors);
            throw $exception;
        }
    }

    private function validateFirstName(string $firstName): void
    {
        if (empty($firstName)) {
            $this->errors['first_name'] = 'First name is required';
        } elseif (strlen($firstName) < 2) {
            $this->errors['first_name'] = 'First name must be at least 2 characters long';
        } elseif (strlen($firstName) > 50) {
            $this->errors['first_name'] = 'First name must not exceed 50 characters';
        }
    }

    private function validateLastName(string $lastName): void
    {
        if (empty($lastName)) {
            $this->errors['last_name'] = 'Last name is required';
        } elseif (strlen($lastName) < 2) {
            $this->errors['last_name'] = 'Last name must be at least 2 characters long';
        } elseif (strlen($lastName) > 50) {
            $this->errors['last_name'] = 'Last name must not exceed 50 characters';
        }
    }

    private function validateEmail(string $email): void
    {
        if (empty($email)) {
            $this->errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format';
        }
    }

    // private function validatePassword(string $password): void
    // {
    //     if (empty($password)) {
    //         $this->errors['password'] = 'Password is required';
    //     } elseif (strlen($password) < 8) {
    //         $this->errors['password'] = 'Password must be at least 8 characters long';
    //     } elseif (!preg_match('/[A-Z]/', $password)) {
    //         $this->errors['password'] = 'Password must contain at least one uppercase letter';
    //     } elseif (!preg_match('/[a-z]/', $password)) {
    //         $this->errors['password'] = 'Password must contain at least one lowercase letter';
    //     } elseif (!preg_match('/[0-9]/', $password)) {
    //         $this->errors['password'] = 'Password must contain at least one number';
    //     }
    // }

    private function validateRole(string $role): void
    {
        $validRoles = ['admin', 'mentor', 'student'];
        if (empty($role)) {
            $this->errors['role'] = 'Role is required';
        } elseif (!in_array($role, $validRoles)) {
            $this->errors['role'] = 'Invalid role. Must be one of: ' . implode(', ', $validRoles);
        }
    }

    private function validateBiography(?string $biography): void
    {
        if ($biography !== null && strlen($biography) > 10) {
            $this->errors['biography'] = 'Biography must not exceed 10 characters';
        }
    }
} 