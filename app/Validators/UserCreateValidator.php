<?php

namespace App\Validators;

use App\Exceptions\InvalidUserDataException;

class UserCreateValidator extends BaseValidator
{
    protected function setRules(): void
    {
        $this->rules = [
            'first_name' => [
                'required' => true,
                'min_length' => 2,
                'max_length' => 50,
                'messages' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 2 characters long',
                    'max_length' => 'First name must not exceed 50 characters'
                ]
            ],
            'last_name' => [
                'required' => true,
                'min_length' => 2,
                'max_length' => 50,
                'messages' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 2 characters long',
                    'max_length' => 'Last name must not exceed 50 characters'
                ]
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'messages' => [
                    'required' => 'Email is required',
                    'email' => 'Invalid email format'
                ]
            ],
            'password' => [
                'required' => true,
                'min_length' => 4,
                'pattern' => [
                    'uppercase' => '/[A-Z]/',
                    'lowercase' => '/[a-z]/',
                    'number' => '/[0-9]/'
                ],
                'messages' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 4 characters long',
                    'uppercase' => 'Password must contain at least one uppercase letter',
                    'lowercase' => 'Password must contain at least one lowercase letter',
                    'number' => 'Password must contain at least one number'
                ]
            ],
            'role' => [
                'required' => true,
                'in' => ['admin', 'mentor', 'student'],
                'messages' => [
                    'required' => 'Role is required',
                    'in' => 'Invalid role. Must be one of: admin, mentor, student'
                ]
            ],
            'biography' => [
                'required' => false,
                'min_length' => 10,
                'messages' => [
                    'min_length' => 'Biography must be at least 10 characters long'
                ]
            ],
            'price' => [
                'required' => false,
                'min' => 0,
                'messages' => [
                    'min' => 'Price must be greater than 0'
                ]
            ]
        ];
    }

    protected function throwValidationException(): void
    {
        $exception = new InvalidUserDataException();
        $exception->setErrors($this->errors);
        throw $exception;
    }
} 