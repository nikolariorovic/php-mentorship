<?php

namespace App\Validators;

use App\Exceptions\InvalidPaymentDataException;

class PaymentValidator extends BaseValidator
{
    protected function setRules(): void
    {
        $this->rules = [
            'appointment_id' => [
                'required' => true,
                'min' => 1,
                'messages' => [
                    'required' => 'Appointment ID is required',
                    'min' => 'Invalid appointment ID'
                ]
            ],
            'price' => [
                'required' => true,
                'min' => 1,
                'messages' => [
                    'required' => 'Price is required',
                    'min' => 'Price must be greater than 0'
                ]
            ],
            'card_number' => [
                'required' => true,
                'min_length' => 13,
                'max_length' => 19,
                'pattern' => [
                    'numbers_only' => '/^[0-9\s]+$/'
                ],
                'custom' => [
                    function($value) {
                        return $this->isValidCardNumber($value) ? true : 'Invalid card number';
                    }
                ],
                'messages' => [
                    'required' => 'Card number is required',
                    'min_length' => 'Card number must be at least 13 digits',
                    'max_length' => 'Card number must not exceed 19 digits',
                    'numbers_only' => 'Card number can only contain numbers and spaces'
                ]
            ],
            'expiry_date' => [
                'required' => true,
                'pattern' => [
                    'date_format' => '/^\d{2}\/\d{2}$/'
                ],
                'custom' => [
                    function($value) {
                        return $this->isValidExpiryDate($value) ? true : 'Invalid expiry date';
                    }
                ],
                'messages' => [
                    'required' => 'Expiry date is required',
                    'date_format' => 'Expiry date must be in MM/YY format'
                ]
            ],
            'cvv' => [
                'required' => true,
                'min_length' => 3,
                'max_length' => 4,
                'pattern' => [
                    'numbers_only' => '/^[0-9]+$/'
                ],
                'messages' => [
                    'required' => 'CVV is required',
                    'min_length' => 'CVV must be at least 3 digits',
                    'max_length' => 'CVV must not exceed 4 digits',
                    'numbers_only' => 'CVV can only contain numbers'
                ]
            ],
            'cardholder_name' => [
                'required' => true,
                'min_length' => 2,
                'max_length' => 100,
                'pattern' => [
                    'letters_spaces' => '/^[a-zA-Z\s]+$/'
                ],
                'messages' => [
                    'required' => 'Cardholder name is required',
                    'min_length' => 'Cardholder name must be at least 2 characters long',
                    'max_length' => 'Cardholder name must not exceed 100 characters',
                    'letters_spaces' => 'Cardholder name can only contain letters and spaces'
                ]
            ]
        ];
    }

    private function isValidCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\s+/', '', $cardNumber);
        
        if (!preg_match('/^[0-9]+$/', $cardNumber)) {
            return false;
        }
        
        $sum = 0;
        $length = strlen($cardNumber);
        $parity = $length % 2;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$cardNumber[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        
        return $sum % 10 == 0;
    }

    private function isValidExpiryDate(string $expiryDate): bool
    {
        if (!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
            return false;
        }
        
        list($month, $year) = explode('/', $expiryDate);
        $month = (int)$month;
        $year = (int)$year;
        
        if ($year < 100) {
            $year += 2000;
        }
        
        if ($month < 1 || $month > 12) {
            return false;
        }
        
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');
        
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            return false;
        }
        
        if ($year > $currentYear + 10) {
            return false;
        }
        
        return true;
    }

    protected function throwValidationException(): void
    {
        $exception = new InvalidPaymentDataException();
        $exception->setErrors($this->errors);
        throw $exception;
    }
} 