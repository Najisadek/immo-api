<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use App\DTOs\UpdatePropertyDTO;
use OpenApi\Attributes as OA;
use App\Models\Property;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Put(
    path: '/api/v1/properties/{property}',
    summary: 'Update a property',
    description: 'Update an existing property. Only the owner or admin can update.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'type', type: 'string', enum: ['apartment', 'house', 'studio', 'land', 'commercial']),
            new OA\Property(property: 'rooms', type: 'integer', nullable: true),
            new OA\Property(property: 'surface', type: 'number', format: 'decimal'),
            new OA\Property(property: 'price', type: 'number', format: 'decimal'),
            new OA\Property(property: 'city', type: 'string', maxLength: 255),
            new OA\Property(property: 'description', type: 'string', minLength: 10, maxLength: 5000),
            new OA\Property(property: 'status', type: 'string', enum: ['available', 'sold', 'rented']),
            new OA\Property(property: 'is_published', type: 'boolean'),
        ]
    )
)]
#[OA\Response(
    response: 200,
    description: 'Property updated',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string'),
            new OA\Property(property: 'data', ref: '#/components/schemas/Property'),
        ]
    )
)]
#[OA\Response(response: 404, description: 'Property not found')]
#[OA\Response(response: 403, description: 'Forbidden')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class UpdatePropertyController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(UpdatePropertyRequest $request, Property $property): JsonResponse
    {
        $dto = UpdatePropertyDTO::fromRequest($request->validated());

        $data = $this->propertyService->update($property, $dto);

        return response()->json([
            'message' => 'Bien immobilier mis à jour avec succès',
            'data' => new PropertyResource($data),
        ]);
    }
}
