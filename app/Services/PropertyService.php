<?php

namespace App\Services;

use App\Contracts\{PropertyImageRepositoryInterface, PropertyRepositoryInterface};
use App\DTOs\{CreatePropertyDTO, FilterPropertyDTO, UpdatePropertyDTO};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\UploadedFile;
use App\Models\{Property, User};

class PropertyService
{
    public function __construct(
        private PropertyRepositoryInterface $propertyRepository,
        private PropertyImageRepositoryInterface $imageRepository
    ) {}

    public function create(CreatePropertyDTO $dto): Property
    {
        Gate::authorize('create', Property::class);

        $property = $this->propertyRepository->create($dto);
        $property->generateTitle();
        $property->save();

        return $property->fresh();
    }

    public function update(Property $property, UpdatePropertyDTO $dto): Property
    {
        Gate::authorize('update', $property);

        return $this->propertyRepository->update($property, $dto);
    }

    public function delete(Property $property): bool
    {
        Gate::authorize('delete', $property);

        $this->imageRepository->deleteByPropertyId($property->id);

        return $this->propertyRepository->delete($property);
    }

    public function getById(int $id, ?User $user = null): Property
    {
        $property = $this->propertyRepository->findByIdWithImages($id);

        if (! $property) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Property not found');
        }

        if (! $property->is_published && $user?->canManageProperty($property) === false) {
            throw new AuthorizationException('This property is not published');
        }

        return $property;
    }

    public function getAll(FilterPropertyDTO $dto): LengthAwarePaginator
    {
        return $this->propertyRepository->filter($dto);
    }

    public function getByUser(User $user): Collection
    {
        return $this->propertyRepository->findByUserId($user->id);
    }

    public function uploadImage(Property $property, UploadedFile $file): Property
    {
        Gate::authorize('update', $property);

        $this->imageRepository->create($property->id, $file);

        return $property->fresh();
    }

    public function deleteImage(Property $property, int $imageId): bool
    {
        Gate::authorize('update', $property);

        $image = $this->imageRepository->findById($imageId);

        if (! $image || $image->property_id !== $property->id) {
            throw new \InvalidArgumentException('Image not found for this property');
        }

        return $this->imageRepository->delete($image);
    }

    public function togglePublish(Property $property): Property
    {
        Gate::authorize('update', $property);

        $property->is_published = ! $property->is_published;
        $property->save();

        return $property;
    }
}
