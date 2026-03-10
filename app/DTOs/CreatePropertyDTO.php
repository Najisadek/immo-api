<?php

declare(strict_types=1);

namespace App\DTOs;

final class CreatePropertyDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $type,
        public readonly ?int $rooms,
        public readonly float $surface,
        public readonly float $price,
        public readonly string $city,
        public readonly string $description,
        public readonly string $status = 'available',
        public readonly bool $isPublished = false,
    ) {}

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            type: $data['type'],
            rooms: $data['rooms'] ?? null,
            surface: (float) $data['surface'],
            price: (float) $data['price'],
            city: $data['city'],
            description: $data['description'],
            status: $data['status'] ?? 'available',
            isPublished: $data['is_published'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'type' => $this->type,
            'rooms' => $this->rooms,
            'surface' => $this->surface,
            'price' => $this->price,
            'city' => $this->city,
            'description' => $this->description,
            'status' => $this->status,
            'is_published' => $this->isPublished,
        ];
    }
}
