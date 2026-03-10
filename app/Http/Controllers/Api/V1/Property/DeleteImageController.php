<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Property;

#[OA\Tag(name: 'Property Images', description: 'Property image management endpoints')]
#[OA\Delete(
    path: '/api/v1/properties/{property}/images/{imageId}',
    summary: 'Delete property image',
    description: 'Delete an image from a property. Only the owner or admin can delete.',
    tags: ['Property Images'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\Parameter(name: 'imageId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\Response(
    response: 200,
    description: 'Image deleted',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string'),
        ]
    )
)]
#[OA\Response(response: 404, description: 'Image not found')]
#[OA\Response(response: 403, description: 'Forbidden')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class DeleteImageController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(Property $property, int $imageId): JsonResponse
    {
        $this->propertyService->deleteImage($property, $imageId);

        return response()->json([
            'message' => 'Image supprimée avec succès',
        ]);
    }
}
