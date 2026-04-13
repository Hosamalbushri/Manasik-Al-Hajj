<?php

use Illuminate\Support\Facades\Route;
use Webkul\Web\Http\Controllers\AdhkarController;
use Webkul\Web\Http\Controllers\Hajj\AccountController as HajjAccountController;
use Webkul\Web\Http\Controllers\Hajj\AccountPreferencesController as HajjAccountPreferencesController;
use Webkul\Web\Http\Controllers\Hajj\Auth\RegisterController as HajjRegisterController;
use Webkul\Web\Http\Controllers\Hajj\Auth\SessionController as HajjSessionController;
use Webkul\Web\Http\Controllers\Hajj\DuaFavoriteController as HajjDuaFavoriteController;
use Webkul\Web\Http\Controllers\HomeController;
use Webkul\Web\Http\Controllers\LocaleController;
use Webkul\Web\Http\Controllers\MapsController;
use Webkul\Web\Http\Middleware\WebLocale;

Route::middleware(['web', WebLocale::class])
    ->prefix('hajj')
    ->name('hajj.')
    ->group(function () {
        Route::middleware('guest:hajj')->group(function () {
            Route::get('login', [HajjSessionController::class, 'create'])->name('session.create');
            Route::post('login', [HajjSessionController::class, 'store'])->name('session.store');

            Route::get('register', [HajjRegisterController::class, 'create'])->name('register.create');
            Route::post('register', [HajjRegisterController::class, 'store'])->name('register.store');
        });

        Route::middleware('auth:hajj')->group(function () {
            Route::post('logout', [HajjSessionController::class, 'destroy'])->name('session.destroy');

            Route::get('account', [HajjAccountController::class, 'index'])->name('account.index');
            Route::patch('account/profile', [HajjAccountController::class, 'updateProfile'])->name('account.profile.update');
            Route::put('account/password', [HajjAccountController::class, 'updatePassword'])->name('account.password.update');
            Route::patch('account/preferences', [HajjAccountPreferencesController::class, 'update'])->name('account.preferences.update');
            Route::post('account/favorites/toggle', [HajjDuaFavoriteController::class, 'toggle'])->name('account.favorites.toggle');
            Route::delete('account/favorites', [HajjDuaFavoriteController::class, 'clear'])->name('account.favorites.clear');
            Route::delete('account/favorites/{dua}', [HajjDuaFavoriteController::class, 'destroy'])->name('account.favorites.destroy');
            Route::delete('account', [HajjAccountController::class, 'destroy'])->name('account.destroy');
        });
    });

Route::middleware(['web', WebLocale::class])->group(function () {
    Route::get('switch-locale/{locale_code}', [LocaleController::class, 'switch'])
        ->name('web.locale.switch');

    Route::get('/', [HomeController::class, 'index'])
        ->name('web.home.index');

    Route::get('maps', [MapsController::class, 'index'])
        ->name('web.maps.index');

    Route::get('adhkar', [AdhkarController::class, 'index'])
        ->name('web.adhkar.index');
});
