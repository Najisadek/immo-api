<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Resources\AuthResource;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;
use OpenApi\Attributes as OA;
use App\DTOs\LoginDTO;

#[OA\Tag(name: 'Authentication', description: 'User authentication endpoints')]
#[OA\Post(
    path: '/api/v1/login',
    summary: 'User login',
    description: 'Authenticate a user with email and password to receive a Bearer token.',
    tags: ['Authentication'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
            ]
        )
    )
)]
#[OA\Response(
    response: 200,
    description: 'Login successful',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Connexion réussie'),
            new OA\Property(
                property: 'data',
                properties: [
                    new OA\Property(property: 'token', type: 'string', example: '1|laravel_sanctum_token...'),
                    new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                    new OA\Property(
                        property: 'user',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                            new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                            new OA\Property(property: 'role', type: 'string', example: 'admin'),
                            new OA\Property(property: 'created_at', type: 'string', example: '2024-03-10 10:00:00'),
                        ],
                        type: 'object'
                    ),
                ],
                type: 'object'
            ),
        ]
    )
)]
#[OA\Response(
    response: 401,
    description: 'Authentication failed',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials'),
        ]
    )
)]
final class LoginController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request->validated());
        $result = $this->authService->login($dto);

        return response()->json([
            'message' => 'Connexion réussie',
            'data' => new AuthResource([
                'token' => $result['token'],
                'user' => $result['user'],
            ]),
        ]);
    }
}
