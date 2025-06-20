<?php

namespace App\Validators;

use App\Exceptions\InvalidUserDataException;
use App\Validators\Interfaces\ValidatorInterface;

abstract class BaseValidator implements ValidatorInterface
{
    protected array $errors = [];
    protected array $rules = [];

    public function __construct()
    {
        $this->setRules();
    }

    abstract protected function setRules(): void;

    public function validate(array $data): void
    {
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $this->validateField($field, $value, $fieldRules);
        }

        if (!empty($this->errors)) {
            $this->throwValidationException();
        }
    }

    protected function validateField(string $field, $value, array $rules): void
    {
        // Required validation
        if (($rules['required'] ?? false) && empty($value)) {
            $this->errors[$field] = $rules['messages']['required'];
            return;
        }

        // Skip other validations if field is not required and empty
        if (empty($value) && !($rules['required'] ?? false)) {
            return;
        }

        // Min length validation
        if (isset($rules['min_length']) && strlen($value) < $rules['min_length']) {
            $this->errors[$field] = $rules['messages']['min_length'];
            return;
        }

        // Max length validation
        if (isset($rules['max_length']) && strlen($value) > $rules['max_length']) {
            $this->errors[$field] = $rules['messages']['max_length'];
            return;
        }

        // Email validation
        if (isset($rules['email']) && $rules['email'] && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $rules['messages']['email'];
            return;
        }

        // In array validation
        if (isset($rules['in']) && !in_array($value, $rules['in'])) {
            $this->errors[$field] = $rules['messages']['in'];
            return;
        }

        // Pattern validation
        if (isset($rules['pattern'])) {
            foreach ($rules['pattern'] as $patternName => $pattern) {
                if (!preg_match($pattern, $value)) {
                    $this->errors[$field] = $rules['messages'][$patternName];
                    return;
                }
            }
        }

        // Min value validation (for numeric fields)
        if (isset($rules['min']) && is_numeric($value) && $value < $rules['min']) {
            $this->errors[$field] = $rules['messages']['min'];
            return;
        }

        // Max value validation (for numeric fields)
        if (isset($rules['max']) && is_numeric($value) && $value > $rules['max']) {
            $this->errors[$field] = $rules['messages']['max'];
            return;
        }

        // Custom validation
        if (isset($rules['custom'])) {
            foreach ($rules['custom'] as $customRule) {
                $result = $customRule($value, $data ?? []);
                if ($result !== true) {
                    $this->errors[$field] = $result;
                    return;
                }
            }
        }
    }

    protected function throwValidationException(): void
    {
        $exceptionClass = InvalidUserDataException::class;
        $exception = new $exceptionClass();
        
        if (method_exists($exception, 'setErrors')) {
            $exception->setErrors($this->errors);
        }
        
        throw $exception;
    }
} 