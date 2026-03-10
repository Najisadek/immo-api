<?php

declare(strict_types=1);

namespace App\DTOs;

final class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
        public readonly ?string $phone = null,
        public readonly ?string $agencyName = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
            phone: $data['phone'] ?? null,
            agencyName: $data['agency_name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'phone' => $this->phone,
            'agency_name' => $this->agencyName,
        ];
    }
}
