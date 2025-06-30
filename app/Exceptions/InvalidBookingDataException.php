<?php

namespace App\Exceptions;

class InvalidBookingDataException extends BaseException
{
    public function __construct(string $message = "Invalid booking data", int $code = 400)
    {
        parent::__construct($message, $code);
    }
} 