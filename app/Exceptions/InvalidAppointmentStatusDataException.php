<?php

namespace App\Exceptions;

class InvalidAppointmentStatusDataException extends BaseException
{
    public function __construct(string $message = "Invalid appointment status data", int $code = 400)
    {
        parent::__construct($message, $code);
    }
} 