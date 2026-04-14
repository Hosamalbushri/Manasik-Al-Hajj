<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\HajjRites\HajjRiteController;

Route::prefix('manasik-hajj-rites')->group(function () {
    Route::controller(HajjRiteController::class)->group(function () {
        Route::get('', 'index')->name('admin.manasik-hajj-rites.index');
        Route::get('create', 'create')->name('admin.manasik-hajj-rites.create');
        Route::post('create', 'store')->name('admin.manasik-hajj-rites.store');
        Route::get('edit/{id}', 'edit')->name('admin.manasik-hajj-rites.edit');
        Route::put('edit/{id}', 'update')->name('admin.manasik-hajj-rites.update');
        Route::delete('{id}', 'destroy')->name('admin.manasik-hajj-rites.destroy');
    });
});
