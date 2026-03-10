<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\{PropertyImageRepositoryInterface, PropertyRepositoryInterface };
use App\Repositories\{PropertyImageRepository, PropertyRepository};
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(PropertyImageRepositoryInterface::class, PropertyImageRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
