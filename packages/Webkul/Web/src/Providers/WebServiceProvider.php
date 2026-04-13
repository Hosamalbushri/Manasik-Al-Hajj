<?php

namespace Webkul\Web\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Webkul\Web\Repositories\WebThemeCustomizationRepository;

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

        $this->app->singleton(WebThemeCustomizationRepository::class, function ($app) {
            return new WebThemeCustomizationRepository($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Authenticate::redirectUsing(function ($request) {
            $admin = trim((string) config('app.admin_path', 'admin'), '/');
            if ($admin !== '' && ($request->is($admin) || $request->is($admin.'/*'))) {
                return route('admin.session.create');
            }

            if ($request->is('hajj') || $request->is('hajj/*')) {
                return route('hajj.session.create');
            }

            return route('admin.session.create');
        });

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'web');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'web');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'web');
    }
}
