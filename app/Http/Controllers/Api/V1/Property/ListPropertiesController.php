<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Requests\FilterPropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use App\DTOs\FilterPropertyDTO;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Get(
    path: '/api/v1/properties',
    summary: 'List Properties',
    description: 'Retrieve a paginated list of properties with optional filters.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'city', in: 'query', required: false, description: 'Filter by city name')]
#[OA\Parameter(name: 'type', in: 'query', required: false, description: 'Filter by property type (apartment, house, studio, land, commercial)')]
#[OA\Parameter(name: 'min_price', in: 'query', required: false, description: 'Minimum price filter')]
#[OA\Parameter(name: 'max_price', in: 'query', required: false, description: 'Maximum price filter')]
#[OA\Parameter(name: 'status', in: 'query', required: false, description: 'Filter by status (available, sold, rented)')]
#[OA\Parameter(name: 'search', in: 'query', required: false, description: 'Search in title and description')]
#[OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Items per page (1-100)')]
#[OA\Parameter(name: 'page', in: 'query', required: false, description: 'Page number')]
#[OA\Response(
    response: 200,
    description: 'Paginated list of properties'
)]
#[OA\Response(response: 401, description: 'Not authenticated')]
final class ListPropertiesController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(FilterPropertyRequest $request): JsonResponse
    {
        $dto = FilterPropertyDTO::fromRequest($request->validated());

        $properties = $this->propertyService->getAll($dto);

        return response()->json([
            'data' => PropertyResource::collection($properties),
            'meta' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ],
        ]);
    }
}
