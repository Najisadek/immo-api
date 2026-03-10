<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;
use OpenApi\Attributes as OA;
use App\DTOs\RegisterDTO;

#[OA\Tag(name: 'Authentication', description: 'User authentication endpoints')]
#[OA\Post(
    path: '/api/v1/register',
    summary: 'Register a new user',
    description: 'Register a new user account and receive an authentication token.',
    tags: ['Authentication'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation', 'role'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                new OA\Property(property: 'role', type: 'string', enum: ['agent', 'guest'], example: 'guest'),
            ]
        )
    )
)]
#[OA\Response(
    response: 201,
    description: 'User registered successfully',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'Inscription réussie'),
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
                            new OA\Property(property: 'role', type: 'string', example: 'guest'),
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
    response: 422,
    description: 'Validation error',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
            new OA\Property(
                property: 'errors',
                properties: [
                    new OA\Property(property: 'email', type: 'array', items: new OA\Items(type: 'string')),
                ],
                type: 'object'
            ),
        ]
    )
)]
final class RegisterController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterDTO::fromRequest($request->validated());
        $result = $this->authService->register($dto);

        return response()->json([
            'message' => 'Inscription réussie',
            'data' => new AuthResource([
                'token' => $result['token'],
                'user' => $result['user'],
            ]),
        ], 201);
    }
}
