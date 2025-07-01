<?php

namespace App\Exceptions;

abstract class BaseException extends \Exception
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

    public function getFirstError(): ?string
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    public function __toString(): string
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()
        ];

        return json_encode($response);
    }
} 