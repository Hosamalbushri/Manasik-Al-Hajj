<?php

namespace Webkul\Hajj\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\ServiceProvider;

class HajjServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'hajj');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'hajj');

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
    }
}
