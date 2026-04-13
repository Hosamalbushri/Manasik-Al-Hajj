<?php

namespace Webkul\Manasik\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Manasik\Repositories\DuaRepository;
use Webkul\Manasik\Repositories\HajjUserRepository;
use Webkul\Manasik\Repositories\MapLocationRepository;

class ManasikServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HajjUserRepository::class, function ($app) {
            return new HajjUserRepository($app);
        });

        $this->app->singleton(MapLocationRepository::class, function ($app) {
            return new MapLocationRepository($app);
        });

        $this->app->singleton(DuaRepository::class, function () {
            return new DuaRepository;
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
