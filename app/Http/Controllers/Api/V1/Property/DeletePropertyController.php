<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Property;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Delete(
    path: '/api/v1/properties/{property}',
    summary: 'Delete a property',
    description: 'Delete a property. Only the owner or admin can delete.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\Response(
    response: 200,
    description: 'Property deleted',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string'),
        ]
    )
)]
#[OA\Response(response: 404, description: 'Property not found')]
#[OA\Response(response: 403, description: 'Forbidden')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class DeletePropertyController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(Property $property): JsonResponse
    {
        $this->propertyService->delete($property);

        return response()->json([
            'message' => 'Bien immobilier supprimé avec succès',
        ]);
    }
}
