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
#[OA\Post(
    path: '/api/v1/properties/{property}/toggle-publish',
    summary: 'Toggle publish status',
    description: 'Toggle the publish status of a property. Only the owner or admin can toggle.',
    tags: ['Properties'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(name: 'property', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
#[OA\Response(
    response: 200,
    description: 'Publish status toggled',
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
final class TogglePublishPropertyController
{
    public function __construct(
        private PropertyService $propertyService
    ) {}

    public function __invoke(Property $property): JsonResponse
    {
        Gate::authorize('togglePublish', $property);

        $data = $this->propertyService->togglePublish($property);

        return response()->json([
            'message' => $data->is_published
                ? 'Bien publié avec succès'
                : 'Bien dépublié avec succès',
            'data' => new PropertyResource($data),
        ]);
    }
}
