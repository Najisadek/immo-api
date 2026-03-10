<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\{JsonResponse, Request};
use App\Services\AuthService;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'User Management', description: 'User management endpoints (Admin only)')]
#[OA\Delete(
    path: '/api/v1/users/{id}',
    summary: 'Delete a user',
    description: 'Delete a user account by ID. Admin only. Soft deletes the user.',
    tags: ['User Management'],
    security: [['bearerAuth' => []]]
)]
#[OA\Parameter(
    name: 'id',
    in: 'path',
    required: true,
    description: 'The ID of the user to delete',
    schema: new OA\Schema(type: 'integer')
)]
#[OA\Response(
    response: 200,
    description: 'User deleted successfully',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Utilisateur supprimé avec succès'),
        ]
    )
)]
#[OA\Response(
    response: 404,
    description: 'User not found',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'User not found'),
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
final class DeleteUserController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(Request $request, int $id): JsonResponse
    {
        $this->authService->deleteUser($id, $request->user());

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }
}
