<?php

namespace App\Providers;

use App\Repositories\CarBrandModelRepository;
use App\Repositories\CarBrandRepository;
use App\Repositories\Interfaces\CarBrandModelRepositoryInterface;
use App\Repositories\Interfaces\CarBrandRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CarBrandRepositoryInterface::class,
            CarBrandRepository::class
        );

        $this->app->bind(
            CarBrandModelRepositoryInterface::class,
            CarBrandModelRepository::class
        );
    }
}
