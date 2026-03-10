<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Property;

use App\Http\Resources\PropertyResource;
use Illuminate\Support\Facades\Auth;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Get(
    path: '/api/v1/properties/my',
    summary: 'List my properties',
    description: 'Retrieve a list of properties owned by the authenticated user.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Response(
    response: 200,
    description: 'List of user properties',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Property')),
        ]
    )
)]
#[OA\Response(response: 401, description: 'Unauthorized')]
final class ListMyPropertiesController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(): JsonResponse
    {
        $user = Auth::user();
        
        $properties = $this->propertyService->getByUser($user);

        return response()->json([
            'data' => PropertyResource::collection($properties),
        ]);
    }
}
