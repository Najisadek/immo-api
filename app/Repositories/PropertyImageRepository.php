<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\PropertyImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use App\Models\PropertyImage;
use Illuminate\Support\Str;

final class PropertyImageRepository implements PropertyImageRepositoryInterface
{
    public function create(int $propertyId, UploadedFile $file): PropertyImage
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('properties/'.$propertyId, $filename, 'public');

        return PropertyImage::create([
            'property_id' => $propertyId,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);
    }

    public function findById(int $id): ?PropertyImage
    {
        return PropertyImage::find($id);
    }

    public function findByPropertyId(int $propertyId): array
    {
        return PropertyImage::where('property_id', $propertyId)->get()->toArray();
    }

    public function delete(PropertyImage $image): bool
    {
        return $image->deleteImage();
    }

    public function deleteByPropertyId(int $propertyId): int
    {
        $images = PropertyImage::where('property_id', $propertyId)->get();
        $count = 0;

        foreach ($images as $image) {
            if ($this->delete($image)) {
                $count++;
            }
        }

        return $count;
    }
}
