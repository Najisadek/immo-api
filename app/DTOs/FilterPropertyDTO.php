<?php

declare(strict_types=1);

namespace App\DTOs;

final class FilterPropertyDTO
{
    public function __construct(
        public readonly ?string $city = null,
        public readonly ?string $type = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly ?string $status = null,
        public readonly ?string $search = null,
        public readonly bool $onlyPublished = true,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            city: $data['city'] ?? null,
            type: $data['type'] ?? null,
            minPrice: isset($data['min_price']) ? (float) $data['min_price'] : null,
            maxPrice: isset($data['max_price']) ? (float) $data['max_price'] : null,
            status: $data['status'] ?? null,
            search: $data['search'] ?? null,
            onlyPublished: $data['only_published'] ?? true,
            perPage: $data['per_page'] ?? 15,
            page: $data['page'] ?? 1,
        );
    }
}
