<?php

namespace App\Validators;

use App\Exceptions\InvalidBookingDataException;

class BookingValidator extends BaseValidator
{
    protected function setRules(): void
    {
        $this->rules = [
            'mentor_id' => [
                'required' => true,
                'min' => 1,
                'messages' => [
                    'required' => 'Mentor ID is required',
                    'min' => 'Invalid mentor ID'
                ]
            ],
            'date' => [
                'required' => true,
                'pattern' => [
                    'date_format' => '/^\d{4}-\d{2}-\d{2}$/'
                ],
                'custom' => [
                    function($value) {
                        $selectedDate = new \DateTime($value);
                        $today = new \DateTime('today');
                        
                        if ($selectedDate < $today) {
                            return 'Cannot book appointments in the past';
                        }
                        
                        $maxDate = new \DateTime('today');
                        $maxDate->add(new \DateInterval('P1M'));
                        
                        if ($selectedDate > $maxDate) {
                            return 'Cannot book appointments more than 1 month in advance';
                        }
                        
                        return true;
                    }
                ],
                'messages' => [
                    'required' => 'Date is required',
                    'date_format' => 'Invalid date format. Expected YYYY-MM-DD'
                ]
            ],
            'time' => [
                'required' => true,
                'messages' => [
                    'required' => 'Time is required'
                ]
            ],
            'price' => [
                'required' => true,
                'min' => 0,
                'messages' => [
                    'required' => 'Price is required',
                    'min' => 'Price must be greater than or equal to 0'
                ]
            ]
        ];
    }

    protected function throwValidationException(): void
    {
        $exception = new InvalidBookingDataException();
        $exception->setErrors($this->errors);
        throw $exception;
    }
} 