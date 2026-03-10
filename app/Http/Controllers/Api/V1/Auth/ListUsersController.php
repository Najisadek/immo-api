<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'User Management', description: 'User management endpoints (Admin only)')]
#[OA\Get(
    path: '/api/v1/users',
    summary: 'List all users',
    description: 'Retrieve a list of all users. Admin only.',
    tags: ['User Management'],
    security: [['bearerAuth' => []]]
)]
#[OA\Response(
    response: 200,
    description: 'List of all users',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'data',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                        new OA\Property(property: 'role', type: 'string', example: 'admin'),
                        new OA\Property(property: 'created_at', type: 'string', example: '2024-03-10 10:00:00'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )
)]
#[OA\Response(
    response: 403,
    description: 'Forbidden - Admin access required',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
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
final class ListUsersController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(): JsonResponse
    {
        $users = $this->authService->getAllUsers();

        return response()->json([
            'data' => UserResource::collection($users),
        ]);
    }
}
