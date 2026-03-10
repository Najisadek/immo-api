<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Property;

#[OA\Tag(name: 'Property Images', description: 'Property image management endpoints')]
#[OA\Post(
    path: '/api/v1/properties/{property}/images',
    summary: 'Upload property image',
    description: 'Upload an image to a property. Supported formats: jpeg, png, jpg, webp. Max size: 5MB.',
    tags: ['Property Images'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\RequestBody(
    required: true,
    content: new OA\MediaType(
        mediaType: 'multipart/form-data',
        schema: new OA\Schema(
            properties: [
                new OA\Property(property: 'image', type: 'string', format: 'binary'),
            ]
        )
    )
)]
#[OA\Response(
    response: 200,
    description: 'Image uploaded',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string'),
            new OA\Property(property: 'data', ref: '#/components/schemas/Property'),
        ]
    )
)]
#[OA\Response(response: 422, description: 'Validation error')]
#[OA\Response(response: 404, description: 'Property not found')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class UploadImageController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(UploadImageRequest $request, Property $property): JsonResponse
    {
        $data = $this->propertyService->uploadImage(
            $property,
            $request->file('image')
        );

        return response()->json([
            'message' => 'Image téléchargée avec succès',
            'data' => new PropertyResource($data),
        ]);
    }
}
