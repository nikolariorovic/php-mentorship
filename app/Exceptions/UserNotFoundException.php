<?php

namespace App\Exceptions;

class UserNotFoundException extends BaseException
{
    public function __construct(string $message = "User not found", int $code = 404)
    {
        parent::__construct($message, $code);
    }
} 