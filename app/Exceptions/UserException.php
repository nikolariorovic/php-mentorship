<?php

namespace App\Exceptions;

class UserException extends \Exception
{
    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }
} 