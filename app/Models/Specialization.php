<?php
namespace App\Models;

use DateTime;

class Specialization
{
    private int $id;
    private string $name;
    private string $description;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    public function __construct(int $id, string $name, string $description, ?string $created_at, ?string $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at ? new DateTime($created_at) : null;
        $this->updated_at = $updated_at ? new DateTime($updated_at) : null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null
        ];
    }
}