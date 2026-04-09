<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Settings\GroupController;
use Webkul\Admin\Http\Controllers\Settings\LocaleController;
use Webkul\Admin\Http\Controllers\Settings\RoleController;
use Webkul\Admin\Http\Controllers\Settings\SettingController;
use Webkul\Admin\Http\Controllers\Settings\WebThemeCustomizationController;
use Webkul\Admin\Http\Controllers\Settings\UserController;

/**
 * Settings routes (minimal: groups, roles, users, shop theme + settings search).
 */
Route::prefix('settings')->group(function () {
    /**
     * Settings routes.
     */
    Route::controller(SettingController::class)->prefix('settings')->group(function () {
        Route::get('', 'index')->name('admin.settings.index');

        Route::get('search', 'search')->name('admin.settings.search');
    });

    /**
     * Locales (store / admin language list).
     */
    Route::controller(LocaleController::class)->prefix('locales')->group(function () {
        Route::get('', 'index')->name('admin.settings.locales.index');

        Route::get('website', 'website')->name('admin.settings.locales.website');

        Route::put('website', 'updateWebsite')->name('admin.settings.locales.website.update');

        Route::post('create', 'store')->name('admin.settings.locales.store');

        Route::get('edit/{id}', 'edit')->name('admin.settings.locales.edit');

        Route::put('edit/{id}', 'update')->name('admin.settings.locales.update');

        Route::delete('{id}', 'destroy')->name('admin.settings.locales.delete');
    });

    /**
     * Groups routes.
     */
    Route::controller(GroupController::class)->prefix('groups')->group(function () {
        Route::get('', 'index')->name('admin.settings.groups.index');

        Route::post('create', 'store')->name('admin.settings.groups.store');

        Route::get('edit/{id}', 'edit')->name('admin.settings.groups.edit');

        Route::put('edit/{id}', 'update')->name('admin.settings.groups.update');

        Route::delete('{id}', 'destroy')->name('admin.settings.groups.delete');
    });

    /**
     * Roles routes.
     */
    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::get('', 'index')->name('admin.settings.roles.index');

        Route::get('create', 'create')->name('admin.settings.roles.create');

        Route::post('create', 'store')->name('admin.settings.roles.store');

        Route::get('edit/{id}', 'edit')->name('admin.settings.roles.edit');

        Route::put('edit/{id}', 'update')->name('admin.settings.roles.update');

        Route::delete('{id}', 'destroy')->name('admin.settings.roles.delete');
    });

    /**
     * Legacy admin URLs (bookmarks): shop-theme → web-theme.
     */
    Route::redirect('shop-theme', 'web-theme')->name('admin.settings.shop-theme.redirect-index');
    Route::redirect('shop-theme/edit/{id}', 'web-theme/edit/{id}')->name('admin.settings.shop-theme.redirect-edit');

    /**
     * Web portal theme customizations (public site sections).
     */
    Route::controller(WebThemeCustomizationController::class)->prefix('web-theme')->group(function () {
        Route::get('', 'index')->name('admin.settings.web-theme.index');

        Route::post('create', 'store')->name('admin.settings.web-theme.store');

        Route::get('edit/{id}', 'edit')->name('admin.settings.web-theme.edit');

        Route::put('edit/{id}', 'update')->name('admin.settings.web-theme.update');

        Route::delete('{id}', 'destroy')->name('admin.settings.web-theme.destroy');
    });

    /**
     * Users Routes.
     */
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('admin.settings.users.index');

        Route::post('create', 'store')->name('admin.settings.users.store');

        Route::get('edit/{id?}', 'edit')->name('admin.settings.users.edit');

        Route::put('edit/{id}', 'update')->name('admin.settings.users.update');

        Route::get('search', 'search')->name('admin.settings.users.search');

        Route::delete('{id}', 'destroy')->name('admin.settings.users.delete');

        Route::post('mass-update', 'massUpdate')->name('admin.settings.users.mass_update');

        Route::post('mass-destroy', 'massDestroy')->name('admin.settings.users.mass_delete');
    });
});
