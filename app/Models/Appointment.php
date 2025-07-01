<?php
namespace App\Models;

class Appointment
{
    private int $id;
    private int $mentor_id;
    private int $student_id;
    private int $specialization_id;
    private string $period;
    private string $status;
    private string $price;
    private string $payment_status;
    private string $rating;
    private string $comment;
    private string $created_at;

    public function __construct(int $id, int $mentor_id, int $student_id, int $specialization_id, string $period, string $status, string $price, string $payment_status, string $rating, string $comment, string $created_at)
    {
        $this->id = $id;
        $this->mentor_id = $mentor_id;
        $this->student_id = $student_id;
        $this->specialization_id = $specialization_id;
        $this->period = $period;
        $this->status = $status;
        $this->price = $price;
        $this->payment_status = $payment_status;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMentorId(): int
    {
        return $this->mentor_id;
    }

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function getSpecializationId(): int
    {
        return $this->specialization_id;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getPaymentStatus(): string
    {
        return $this->payment_status;
    }

    public function getRating(): string
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mentor_id' => $this->mentor_id,
            'student_id' => $this->student_id,
            'specialization_id' => $this->specialization_id,
            'period' => $this->period,
            'status' => $this->status,
            'price' => $this->price,
            'payment_status' => $this->payment_status,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}