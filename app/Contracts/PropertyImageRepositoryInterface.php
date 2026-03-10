<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\PropertyImage;

interface PropertyImageRepositoryInterface
{
    public function create(int $propertyId, UploadedFile $file): PropertyImage;

    public function findById(int $id): ?PropertyImage;

    public function findByPropertyId(int $propertyId): array;

    public function delete(PropertyImage $image): bool;

    public function deleteByPropertyId(int $propertyId): int;
}
