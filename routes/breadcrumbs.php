<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(trans('admin::app.layouts.dashboard'), route('admin.dashboard.index'));
});

// Settings
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin::app.layouts.settings'), route('admin.settings.index'));
});

// Settings > Locales
Breadcrumbs::for('settings.locales', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(trans('admin::app.layouts.locales'), route('admin.settings.locales.index'));
});

// Settings > Locales > Website
Breadcrumbs::for('settings.locales.website', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.locales');
    $trail->push(trans('admin::app.settings.locales.website.title'), route('admin.settings.locales.website'));
});

// Settings > Groups
Breadcrumbs::for('settings.groups', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(trans('admin::app.layouts.groups'), route('admin.settings.groups.index'));
});

// Settings > Roles
Breadcrumbs::for('settings.roles', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(trans('admin::app.layouts.roles'), route('admin.settings.roles.index'));
});

// Dashboard > Roles > Create Role
Breadcrumbs::for('settings.roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.roles');
    $trail->push(trans('admin::app.settings.roles.create.title'), route('admin.settings.roles.create'));
});

// Dashboard > Roles > Edit Role
Breadcrumbs::for('settings.roles.edit', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('settings.roles');
    $trail->push(trans('admin::app.settings.roles.edit.title'), route('admin.settings.roles.edit', $role->id));
});

// Settings > Users
Breadcrumbs::for('settings.users', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(trans('admin::app.layouts.users'), route('admin.settings.users.index'));
});

// Dashboard > Users > Edit User
Breadcrumbs::for('settings.users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('settings.users');
    $trail->push(trans('admin::app.settings.users.edit-title'), route('admin.settings.users.edit', $user->id));
});

// Settings > Web portal theme
Breadcrumbs::for('settings.web_theme', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(trans('admin::app.settings.web-theme.index.title'), route('admin.settings.web-theme.index'));
});

Breadcrumbs::for('settings.web_theme.edit', function (BreadcrumbTrail $trail, $theme) {
    $trail->parent('settings.web_theme');
    $id = is_object($theme) ? $theme->id : $theme;
    $trail->push(trans('admin::app.settings.web-theme.edit.title'), route('admin.settings.web-theme.edit', $id));
});

// Map locations (top-level area)
Breadcrumbs::for('map_locations', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin::app.settings.map-locations.index.title'), route('admin.map-locations.index'));
});

Breadcrumbs::for('map_locations.create', function (BreadcrumbTrail $trail) {
    $trail->parent('map_locations');
    $trail->push(trans('admin::app.settings.map-locations.create.title'), route('admin.map-locations.create'));
});

Breadcrumbs::for('map_locations.edit', function (BreadcrumbTrail $trail, $location) {
    $trail->parent('map_locations');
    $id = is_object($location) ? $location->id : $location;
    $trail->push(trans('admin::app.settings.map-locations.edit.title'), route('admin.map-locations.edit', $id));
});

// Dhikr & duas (top-level area)
Breadcrumbs::for('adhkar_duas', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin::app.settings.adhkar-duas.menu-title'), route('admin.adhkar-duas.dua-sections.index'));
});

Breadcrumbs::for('adhkar_duas.dua_sections', function (BreadcrumbTrail $trail) {
    $trail->parent('adhkar_duas');
    $trail->push(trans('admin::app.settings.adhkar-duas.menu-sections'), route('admin.adhkar-duas.dua-sections.index'));
});

Breadcrumbs::for('adhkar_duas.dua_sections.create', function (BreadcrumbTrail $trail) {
    $trail->parent('adhkar_duas.dua_sections');
    $trail->push(trans('admin::app.settings.dua-sections.create.title'), route('admin.adhkar-duas.dua-sections.create'));
});

Breadcrumbs::for('adhkar_duas.dua_sections.edit', function (BreadcrumbTrail $trail, $section) {
    $trail->parent('adhkar_duas.dua_sections');
    $id = is_object($section) ? $section->id : $section;
    $trail->push(trans('admin::app.settings.dua-sections.edit.title'), route('admin.adhkar-duas.dua-sections.edit', $id));
});

Breadcrumbs::for('adhkar_duas.duas', function (BreadcrumbTrail $trail) {
    $trail->parent('adhkar_duas');
    $trail->push(trans('admin::app.settings.adhkar-duas.menu-duas'), route('admin.adhkar-duas.duas.index'));
});

Breadcrumbs::for('adhkar_duas.duas.create', function (BreadcrumbTrail $trail) {
    $trail->parent('adhkar_duas.duas');
    $trail->push(trans('admin::app.settings.duas.create.title'), route('admin.adhkar-duas.duas.create'));
});

Breadcrumbs::for('adhkar_duas.duas.edit', function (BreadcrumbTrail $trail, $dua) {
    $trail->parent('adhkar_duas.duas');
    $id = is_object($dua) ? $dua->id : $dua;
    $trail->push(trans('admin::app.settings.duas.edit.title'), route('admin.adhkar-duas.duas.edit', $id));
});

// Configuration
Breadcrumbs::for('configuration', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin::app.layouts.configuration'), route('admin.configuration.index'));
});

// Configuration > Config
Breadcrumbs::for('configuration.slug', function (BreadcrumbTrail $trail, $slug) {
    $trail->parent('configuration');
    $trail->push('', route('admin.configuration.index', ['slug' => $slug]));
});

// Dashboard > Account > Edit
Breadcrumbs::for('dashboard.account.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(trans('admin::app.account.edit.title'), route('admin.user.account.edit'));
});

