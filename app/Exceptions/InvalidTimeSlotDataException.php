<?php

namespace App\Exceptions;

use Exception;

class InvalidTimeSlotDataException extends BaseException
{
    public function __construct(string $message = "Invalid time slot data", int $code = 400)
    {
        parent::__construct($message, $code);
    }
} 