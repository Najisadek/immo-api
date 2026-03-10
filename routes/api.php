<?php

use App\Http\Controllers\Api\V1\Auth\DeleteUserController;
use App\Http\Controllers\Api\V1\Auth\ListUsersController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Property\DeleteImageController;
use App\Http\Controllers\Api\V1\Property\DeletePropertyController;
use App\Http\Controllers\Api\V1\Property\ListMyPropertiesController;
use App\Http\Controllers\Api\V1\Property\ListPropertiesController;
use App\Http\Controllers\Api\V1\Property\ShowPropertyController;
use App\Http\Controllers\Api\V1\Property\StorePropertyController;
use App\Http\Controllers\Api\V1\Property\TogglePublishPropertyController;
use App\Http\Controllers\Api\V1\Property\UpdatePropertyController;
use App\Http\Controllers\Api\V1\Property\UploadImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', LogoutController::class);
        Route::get('/me', MeController::class);

        // User Management (Admin only)
        Route::get('/users', ListUsersController::class);
        Route::delete('/users/{id}', DeleteUserController::class);

        // Properties
        Route::get('/properties', ListPropertiesController::class);
        Route::get('/properties/my', ListMyPropertiesController::class);
        Route::post('/properties', StorePropertyController::class);
        Route::get('/properties/{property}', ShowPropertyController::class);
        Route::put('/properties/{property}', UpdatePropertyController::class);
        Route::delete('/properties/{property}', DeletePropertyController::class);
        Route::post('/properties/{property}/images', UploadImageController::class);
        Route::delete('/properties/{property}/images/{imageId}', DeleteImageController::class);
        Route::post('/properties/{property}/toggle-publish', TogglePublishPropertyController::class);
    });
});
