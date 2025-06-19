<?php
namespace App\Models;

class Admin extends User
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->setRole('admin');
    }
} 