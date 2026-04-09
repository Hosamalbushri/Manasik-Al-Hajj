<?php

return [
    [
        'key' => 'dashboard',
        'name' => 'admin::app.layouts.dashboard',
        'route' => ['admin.dashboard.index', 'admin.dashboard.stats'],
        'sort' => 1,
    ], [
        'key' => 'settings',
        'name' => 'admin::app.acl.settings',
        'route' => [
            'admin.settings.index',
            'admin.settings.search',
            'admin.settings.locales.index',
            'admin.settings.locales.website',
            'admin.settings.locales.website.update',
        ],
        'sort' => 4,
    ], [
        'key' => 'settings.locales',
        'name' => 'admin::app.acl.locales',
        'route' => ['admin.settings.locales.index', 'admin.settings.locales.website'],
        'sort' => 2,
    ], [
        'key' => 'settings.locales.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.locales.store',
        'sort' => 1,
    ], [
        'key' => 'settings.locales.edit',
        'name' => 'admin::app.acl.edit',
        'route' => ['admin.settings.locales.edit', 'admin.settings.locales.update', 'admin.settings.locales.website.update'],
        'sort' => 2,
    ], [
        'key' => 'settings.locales.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.locales.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.locales.catalog',
        'name' => 'admin::app.settings.locales.tabs.catalog',
        'route' => 'admin.settings.locales.index',
        'sort' => 0,
    ], [
        'key' => 'settings.locales.website',
        'name' => 'admin::app.settings.locales.tabs.website',
        'route' => ['admin.settings.locales.website', 'admin.settings.locales.website.update'],
        'sort' => 1,
    ], [
        'key' => 'settings.user',
        'name' => 'admin::app.acl.user',
        'route' => ['admin.settings.groups.index', 'admin.settings.roles.index', 'admin.settings.users.index'],
        'sort' => 1,
    ], [
        'key' => 'settings.user.groups',
        'name' => 'admin::app.acl.groups',
        'route' => 'admin.settings.groups.index',
        'sort' => 1,
    ], [
        'key' => 'settings.user.groups.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.groups.store',
        'sort' => 1,
    ], [
        'key' => 'settings.user.groups.edit',
        'name' => 'admin::app.acl.edit',
        'route' => ['admin.settings.groups.edit', 'admin.settings.groups.update'],
        'sort' => 2,
    ], [
        'key' => 'settings.user.groups.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.groups.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.user.roles',
        'name' => 'admin::app.acl.roles',
        'route' => 'admin.settings.roles.index',
        'sort' => 2,
    ], [
        'key' => 'settings.user.roles.create',
        'name' => 'admin::app.acl.create',
        'route' => ['admin.settings.roles.create', 'admin.settings.roles.store'],
        'sort' => 1,
    ], [
        'key' => 'settings.user.roles.edit',
        'name' => 'admin::app.acl.edit',
        'route' => ['admin.settings.roles.edit', 'admin.settings.roles.update'],
        'sort' => 2,
    ], [
        'key' => 'settings.user.roles.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.roles.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.user.users',
        'name' => 'admin::app.acl.users',
        'route' => ['admin.settings.users.index', 'admin.settings.users.search'],
        'sort' => 3,
    ], [
        'key' => 'settings.user.users.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.users.store',
        'sort' => 1,
    ], [
        'key' => 'settings.user.users.edit',
        'name' => 'admin::app.acl.edit',
        'route' => ['admin.settings.users.edit', 'admin.settings.users.update', 'admin.settings.users.mass_update'],
        'sort' => 2,
    ], [
        'key' => 'settings.user.users.delete',
        'name' => 'admin::app.acl.delete',
        'route' => ['admin.settings.users.delete', 'admin.settings.users.mass_delete'],
        'sort' => 3,
    ], [
        'key' => 'settings.web_theme',
        'name' => 'admin::app.settings.web-theme.acl.title',
        'route' => 'admin.settings.web-theme.index',
        'sort' => 3,
    ], [
        'key' => 'settings.web_theme.homepage',
        'name' => 'admin::app.settings.web-theme.acl.homepage',
        'route' => [
            'admin.settings.web-theme.index',
            'admin.settings.web-theme.edit',
            'admin.settings.web-theme.update',
            'admin.settings.web-theme.store',
            'admin.settings.web-theme.destroy',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.web_theme.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.web-theme.store',
        'sort' => 1,
    ], [
        'key' => 'settings.web_theme.edit',
        'name' => 'admin::app.acl.edit',
        'route' => ['admin.settings.web-theme.edit', 'admin.settings.web-theme.update'],
        'sort' => 2,
    ], [
        'key' => 'settings.web_theme.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.web-theme.destroy',
        'sort' => 3,
    ], [
        'key' => 'configuration',
        'name' => 'admin::app.acl.configuration',
        'route' => [
            'admin.configuration.index',
            'admin.configuration.store',
            'admin.configuration.search',
            'admin.configuration.download',
        ],
        'sort' => 5,
    ],
];
