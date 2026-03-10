<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Immo API Documentation',
    title: 'Immo API',
)]
#[OA\Server(
    url: '/',
    description: 'API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter token in format: Bearer {token}'
)]
#[OA\Tag(name: 'Authentication', description: 'User authentication endpoints')]
#[OA\Tag(name: 'User Management', description: 'User management endpoints (Admin only)')]
#[OA\Tag(name: 'Properties', description: 'Property management endpoints')]
#[OA\Tag(name: 'Property Images', description: 'Property image management endpoints')]
#[OA\Schema(
    schema: 'Property',
    title: 'Property',
    description: 'Real estate property',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', format: 'int64', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Appartement 3 pièces à Paris'),
        new OA\Property(property: 'type', type: 'string', enum: ['apartment', 'house', 'studio', 'land', 'commercial'], example: 'apartment'),
        new OA\Property(property: 'rooms', type: 'integer', nullable: true, example: 3),
        new OA\Property(property: 'surface', type: 'number', format: 'decimal', example: 75.50),
        new OA\Property(property: 'price', type: 'number', format: 'decimal', example: 450000.00),
        new OA\Property(property: 'city', type: 'string', example: 'Paris'),
        new OA\Property(property: 'description', type: 'string', example: 'Magnifique appartement lumineux'),
        new OA\Property(property: 'status', type: 'string', enum: ['available', 'sold', 'pending'], example: 'available'),
        new OA\Property(property: 'is_published', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-03-10 10:00:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-03-10 10:00:00'),
    ]
)]
abstract class Controller
{
    //
}
