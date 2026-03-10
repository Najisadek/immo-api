<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Resources\PropertyResource;
use Illuminate\Support\Facades\Auth;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use App\DTOs\CreatePropertyDTO;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Post(
    path: '/api/v1/properties',
    summary: 'Create a new property',
    description: 'Create a new property listing.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(
        required: ['type', 'surface', 'price', 'city', 'description', 'status'],
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
    response: 201,
    description: 'Property created',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string'),
            new OA\Property(property: 'data', ref: '#/components/schemas/Property'),
        ]
    )
)]
#[OA\Response(response: 422, description: 'Validation error')]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class StorePropertyController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(StorePropertyRequest $request): JsonResponse
    {
        $user = Auth::user();

        $dto = CreatePropertyDTO::fromRequest($request->validated(), $user->id);

        $data = $this->propertyService->create($dto);

        return response()->json([
            'message' => 'Bien immobilier créé avec succès',
            'data' => new PropertyResource($data),
        ], 201);
    }
}
