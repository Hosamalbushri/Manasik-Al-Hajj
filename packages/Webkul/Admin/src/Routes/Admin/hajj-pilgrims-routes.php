<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\HajjPilgrims\HajjUserController;

Route::prefix('manasik-hajj-users')->group(function () {
    Route::controller(HajjUserController::class)->group(function () {
        Route::get('', 'index')->name('admin.manasik-hajj-users.index');
        Route::get('show/{id}', 'show')->name('admin.manasik-hajj-users.show');
    });
});
