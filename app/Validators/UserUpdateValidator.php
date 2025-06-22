<?php

namespace App\Validators;

class UserUpdateValidator extends BaseValidator
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
            'role' => [
                'required' => true,
                'in' => ['admin', 'mentor', 'student'],
                'messages' => [
                    'required' => 'Role is required',
                    'in' => 'Invalid role. Must be one of: admin, mentor, student'
                ]
            ],
            'biography' => [
                'required' => true,
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
} 