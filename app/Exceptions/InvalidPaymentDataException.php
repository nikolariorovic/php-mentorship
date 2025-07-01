<?php
namespace App\Exceptions;

class InvalidPaymentDataException extends BaseException
{
    public function __construct(string $message = "Invalid payment data", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}