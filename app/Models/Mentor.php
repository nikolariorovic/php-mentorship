<?php
namespace App\Models;

class Mentor extends User
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->setRole('mentor');
        $this->setPrice((float)$data['price'] ?? 0.00);
    }
} 