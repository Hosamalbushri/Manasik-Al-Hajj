<?php

use Illuminate\Support\Facades\Route;
use Webkul\Web\Http\Controllers\HomeController;
use Webkul\Web\Http\Controllers\LocaleController;
use Webkul\Web\Http\Middleware\WebLocale;

Route::middleware(['web', WebLocale::class])->group(function () {
    Route::get('switch-locale/{locale_code}', [LocaleController::class, 'switch'])
        ->name('web.locale.switch');

    Route::get('/', [HomeController::class, 'index'])
        ->name('web.home.index');
});
