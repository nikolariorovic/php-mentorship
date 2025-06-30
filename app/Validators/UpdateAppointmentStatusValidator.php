<?php

namespace App\Validators;

use App\Exceptions\InvalidAppointmentStatusDataException;

class UpdateAppointmentStatusValidator extends BaseValidator
{
    protected function setRules(): void
    {
        $this->rules = [
            'appointment_id' => [
                'required' => true,
                'messages' => [
                    'required' => 'Appointment ID is required',
                ]
            ],
            'status' => [
                'required' => true,
                'in' => ['pending', 'accepted', 'finished', 'rejected', 'paid'],
                'messages' => [
                    'required' => 'Status is required',
                    'in' => 'Invalid status'
                ]
            ]
        ];
    }

    protected function throwValidationException(): void
    {
        $exception = new InvalidAppointmentStatusDataException();
        $exception->setErrors($this->errors);
        throw $exception;
    }
} 