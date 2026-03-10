<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\JsonResponse;
use App\Services\AuthService;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

#[OA\Tag(name: 'Authentication', description: 'User authentication endpoints')]
#[OA\Post(
    path: '/api/v1/logout',
    summary: 'User logout',
    description: 'Revoke the current authentication token. Requires Bearer token authentication.',
    tags: ['Authentication'],
    security: [['bearerAuth' => []]]
)]
#[OA\Response(
    response: 200,
    description: 'Logout successful',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Déconnexion réussie'),
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
final class LogoutController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}
