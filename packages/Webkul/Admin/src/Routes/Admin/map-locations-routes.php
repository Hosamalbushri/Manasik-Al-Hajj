<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\MapLocations\WebMapLocationController;

/**
 * Public maps page locations (not under /settings).
 */
Route::prefix('map-locations')->group(function () {
    Route::controller(WebMapLocationController::class)->group(function () {
        Route::get('', 'index')->name('admin.map-locations.index');

        Route::get('create', 'create')->name('admin.map-locations.create');

        Route::post('create', 'store')->name('admin.map-locations.store');

        Route::get('edit/{id}', 'edit')->name('admin.map-locations.edit');

        Route::put('edit/{id}', 'update')->name('admin.map-locations.update');

        Route::delete('{id}', 'destroy')->name('admin.map-locations.destroy');
    });
});
