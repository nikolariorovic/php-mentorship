<?php

namespace App\Exceptions;

class UserNotFoundException extends UserException
{
    public function __construct(string $message = "User not found", int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function __toString(): string
    {
        $response = [
            'error' => true,
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()
        ];

        return json_encode($response);
    }
} 