<?php

namespace App\Exceptions;

class InvalidUserDataException extends UserException
{
    public function __construct(string $message = "Invalid user data", int $code = 400)
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