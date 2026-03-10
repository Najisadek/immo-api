<?php

namespace App\Contracts;

use App\DTOs\{CreatePropertyDTO, FilterPropertyDTO, UpdatePropertyDTO};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Property;

interface PropertyRepositoryInterface
{
    public function create(CreatePropertyDTO $dto): Property;

    public function update(Property $property, UpdatePropertyDTO $dto): Property;

    public function delete(Property $property): bool;

    public function findById(int $id): ?Property;

    public function findByIdWithImages(int $id): ?Property;

    public function findByUserId(int $userId): Collection;

    public function filter(FilterPropertyDTO $dto): LengthAwarePaginator;

    public function search(string $query): Collection;

    public function getAllPublished(): Collection;
}
