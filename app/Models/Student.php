<?php
namespace App\Models;

class Student extends User
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->role = 'student';
    }
} 