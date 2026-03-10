<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Resources\PropertyResource;
use Illuminate\Support\Facades\Gate;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Property;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Get(
    path: '/api/v1/properties/{property}',
    summary: 'Get property details',
    description: 'Retrieve detailed information about a specific property.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\Response(
    response: 200,
    description: 'Property details',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Property'),
        ]
    )
)]
#[OA\Response(response: 404, description: 'Property not found')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class ShowPropertyController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(Property $property): JsonResponse
    {
        Gate::authorize('view', $property);

        $data = $this->propertyService->getById($property->id);

        return response()->json([
            'data' => new PropertyResource($data),
        ]);
    }
}
