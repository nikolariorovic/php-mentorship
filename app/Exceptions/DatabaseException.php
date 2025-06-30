<?php
namespace App\Exceptions;

class DatabaseException extends BaseException {
    public function __construct(string $message = "Database error occurred", int $code = 500, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}