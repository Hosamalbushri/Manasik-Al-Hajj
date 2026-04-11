<?php

namespace Webkul\Web\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/web.php',
            'web'
        );

        $this->app->singleton(\Webkul\Web\Repositories\WebThemeCustomizationRepository::class, function ($app) {
            return new \Webkul\Web\Repositories\WebThemeCustomizationRepository($app);
        });

        $this->app->singleton(\Webkul\Web\Repositories\WebMapLocationRepository::class, function ($app) {
            return new \Webkul\Web\Repositories\WebMapLocationRepository($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'web');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'web');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'web');
    }
}
