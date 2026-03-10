<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\{CreatePropertyDTO, FilterPropertyDTO, UpdatePropertyDTO};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Contracts\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Property;

final class PropertyRepository implements PropertyRepositoryInterface
{
    public function create(CreatePropertyDTO $dto): Property
    {
        return Property::create($dto->toArray());
    }

    public function update(Property $property, UpdatePropertyDTO $dto): Property
    {
        $property->update($dto->toArray());

        return $property->fresh();
    }

    public function delete(Property $property): bool
    {
        return $property->delete();
    }

    public function findById(int $id): ?Property
    {
        return Property::find($id);
    }

    public function findByIdWithImages(int $id): ?Property
    {
        return Property::with(['images', 'user'])->find($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return Property::where('user_id', $userId)->get();
    }

    public function filter(FilterPropertyDTO $dto): LengthAwarePaginator
    {
        $query = Property::query();

        if ($dto->onlyPublished) {
            $query->published();
        }

        if ($dto->city) {
            $query->byCity($dto->city);
        }

        if ($dto->type) {
            $query->byType($dto->type);
        }

        if ($dto->minPrice !== null || $dto->maxPrice !== null) {
            $query->byPriceRange($dto->minPrice, $dto->maxPrice);
        }

        if ($dto->status) {
            $query->byStatus($dto->status);
        }

        if ($dto->search) {
            $query->search($dto->search);
        }

        return $query->with(['images', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    public function search(string $query): Collection
    {
        return Property::query()
            ->search($query)
            ->published()
            ->with(['images', 'user'])
            ->get();
    }

    public function getAllPublished(): Collection
    {
        return Property::query()
            ->published()
            ->with(['images', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
