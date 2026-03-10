<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Services\AuthService;
use Illuminate\Http\Request;

#[OA\Tag(name: 'Authentication', description: 'User authentication endpoints')]
#[OA\Get(
    path: '/api/v1/me',
    summary: 'Get current user',
    description: 'Retrieve information about the currently authenticated user.',
    tags: ['Authentication'],
    security: [['bearerAuth' => []]]
)]
#[OA\Response(
    response: 200,
    description: 'Current user information',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'data',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                    new OA\Property(property: 'role', type: 'string', example: 'admin'),
                    new OA\Property(property: 'created_at', type: 'string', example: '2024-03-10 10:00:00'),
                ],
                type: 'object'
            ),
        ]
    )
)]
#[OA\Response(
    response: 401,
    description: 'Not authenticated',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
        ]
    )
)]
final class MeController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser($request->user());

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }
}
