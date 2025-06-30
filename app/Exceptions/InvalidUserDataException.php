<?php

namespace App\Exceptions;

class InvalidUserDataException extends BaseException
{
    public function __construct(string $message = "Invalid user data", int $code = 400)
    {
        parent::__construct($message, $code);
    }
} 