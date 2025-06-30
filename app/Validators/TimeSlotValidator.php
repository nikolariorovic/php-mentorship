<?php

namespace App\Validators;

use App\Exceptions\InvalidTimeSlotDataException;

class TimeSlotValidator extends BaseValidator
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
                            return 'Cannot check available slots for past dates';
                        }
                        
                        $maxDate = new \DateTime('today');
                        $maxDate->add(new \DateInterval('P1M'));
                        
                        if ($selectedDate > $maxDate) {
                            return 'Cannot check available slots more than 1 month in advance';
                        }
                        
                        return true;
                    }
                ],
                'messages' => [
                    'required' => 'Date is required',
                    'date_format' => 'Invalid date format. Expected YYYY-MM-DD'
                ]
            ]
        ];
    }

    protected function throwValidationException(): void
    {
        $exception = new InvalidTimeSlotDataException();
        $exception->setErrors($this->errors);
        throw $exception;
    }
} 