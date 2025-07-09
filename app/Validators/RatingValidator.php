<?php
namespace App\Validators;

use App\Exceptions\InvalidArgumentException;

class RatingValidator extends BaseValidator {
    protected function setRules(): void
    {
        $this->rules = [
            'rating' => [
                'required' => true,
                'min' => 1,
                'max' => 5,
                'messages' => [
                    'required' => 'Rating is required',
                    'min' => 'Rating must be between 1 and 5',
                    'max' => 'Rating must be between 1 and 5'
                ]
            ],
            'appointment_id' => [
                'required' => true,
                'min' => 1,
                'messages' => [
                    'required' => 'Appointment ID is required',
                    'min' => 'Invalid appointment ID'
                ]
            ],
            'comment' => [
                'required' => true,
                'min' => 1,
                'max' => 255,
                'messages' => [
                    'required' => 'Comment is required',
                    'min' => 'Comment must be at least 1 character',
                    'max' => 'Comment must be less than 255 characters'
                ]
            ]
        ];
    }

    protected function throwValidationException(): void
    {
        throw new InvalidArgumentException('Invalid rating data');
    }
}