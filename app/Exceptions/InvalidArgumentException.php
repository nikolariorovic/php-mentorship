<?php
namespace App\Exceptions;

class InvalidArgumentException extends BaseException
{
    public function __construct(string $message = "Invalid argument", int $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function __toString(): string
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
            'logout' => true
        ];

        return json_encode($response);
    }
}