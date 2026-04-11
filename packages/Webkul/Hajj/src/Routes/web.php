<?php

use Illuminate\Support\Facades\Route;
use Webkul\Hajj\Http\Controllers\AccountController;
use Webkul\Hajj\Http\Controllers\RegisterController;
use Webkul\Hajj\Http\Controllers\SessionController;
use Webkul\Web\Http\Middleware\WebLocale;

Route::middleware(['web', WebLocale::class])
    ->prefix('hajj')
    ->name('hajj.')
    ->group(function () {
        Route::middleware('guest:hajj')->group(function () {
            Route::get('login', [SessionController::class, 'create'])->name('session.create');
            Route::post('login', [SessionController::class, 'store'])->name('session.store');

            Route::get('register', [RegisterController::class, 'create'])->name('register.create');
            Route::post('register', [RegisterController::class, 'store'])->name('register.store');
        });

        Route::middleware('auth:hajj')->group(function () {
            Route::get('account', [AccountController::class, 'index'])->name('account.index');
            Route::post('logout', [SessionController::class, 'destroy'])->name('session.destroy');
        });
    });
