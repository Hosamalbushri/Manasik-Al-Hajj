<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\AdhkarDuas\WebDuaController;
use Webkul\Admin\Http\Controllers\AdhkarDuas\WebDuaSectionController;

/**
 * Dhikr & duas (top-level admin area; not under /settings).
 */
Route::prefix('adhkar-duas')->group(function () {
    Route::controller(WebDuaSectionController::class)->prefix('dua-sections')->group(function () {
        Route::get('', 'index')->name('admin.adhkar-duas.dua-sections.index');

        Route::get('create', 'create')->name('admin.adhkar-duas.dua-sections.create');

        Route::post('create', 'store')->name('admin.adhkar-duas.dua-sections.store');

        Route::get('edit/{id}', 'edit')->name('admin.adhkar-duas.dua-sections.edit');

        Route::put('edit/{id}', 'update')->name('admin.adhkar-duas.dua-sections.update');

        Route::delete('{id}', 'destroy')->name('admin.adhkar-duas.dua-sections.destroy');
    });

    Route::controller(WebDuaController::class)->prefix('duas')->group(function () {
        Route::get('', 'index')->name('admin.adhkar-duas.duas.index');

        Route::get('create', 'create')->name('admin.adhkar-duas.duas.create');

        Route::post('create', 'store')->name('admin.adhkar-duas.duas.store');

        Route::get('edit/{id}', 'edit')->name('admin.adhkar-duas.duas.edit');

        Route::put('edit/{id}', 'update')->name('admin.adhkar-duas.duas.update');

        Route::delete('{id}', 'destroy')->name('admin.adhkar-duas.duas.destroy');
    });
});
