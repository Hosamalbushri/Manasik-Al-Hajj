<?php

return [
    /**
     * Dashboard.
     */
    [
        'key' => 'dashboard',
        'name' => 'admin::app.layouts.dashboard',
        'route' => 'admin.dashboard.index',
        'sort' => 1,
        'icon-class' => 'icon-dashboard',
    ],

    /**
     * Settings.
     */
    [
        'key' => 'settings',
        'name' => 'admin::app.layouts.settings',
        'route' => 'admin.settings.index',
        'sort' => 8,
        'icon-class' => 'icon-setting',
    ], [
        'key' => 'settings.locales',
        'name' => 'admin::app.layouts.locales',
        'route' => 'admin.settings.locales.index',
        'info' => 'admin::app.layouts.locales-info',
        'sort' => 0,
        'icon-class' => 'icon-configuration',
    ], [
        'key' => 'settings.locales.catalog',
        'name' => 'admin::app.settings.locales.tabs.catalog',
        'route' => 'admin.settings.locales.index',
        'info' => 'admin::app.settings.locales.tabs.catalog-info',
        'sort' => 1,
        'icon-class' => 'icon-configuration',
    ], [
        'key' => 'settings.locales.website',
        'name' => 'admin::app.settings.locales.tabs.website',
        'route' => 'admin.settings.locales.website',
        'info' => 'admin::app.settings.locales.tabs.website-info',
        'sort' => 2,
        'icon-class' => 'icon-configuration',
    ], [
        'key' => 'settings.user',
        'name' => 'admin::app.layouts.user',
        'route' => 'admin.settings.groups.index',
        'info' => 'admin::app.layouts.user-info',
        'sort' => 1,
        'icon-class' => 'icon-settings-group',
    ], [
        'key' => 'settings.user.roles',
        'name' => 'admin::app.layouts.roles',
        'info' => 'admin::app.layouts.roles-info',
        'route' => 'admin.settings.roles.index',
        'sort' => 2,
        'icon-class' => 'icon-role',
    ], [
        'key' => 'settings.user.users',
        'name' => 'admin::app.layouts.users',
        'info' => 'admin::app.layouts.users-info',
        'route' => 'admin.settings.users.index',
        'sort' => 3,
        'icon-class' => 'icon-user',
    ], [
        'key' => 'settings.web_theme',
        'name' => 'admin::app.settings.web-theme.section-title',
        'route' => 'admin.settings.web-theme.index',
        'info' => 'admin::app.settings.web-theme.section-info',
        'sort' => 4,
        'icon-class' => 'icon-settings',
    ], [
        'key' => 'settings.web_theme.homepage',
        'name' => 'admin::app.settings.web-theme.index.title',
        'route' => 'admin.settings.web-theme.index',
        'info' => 'admin::app.settings.web-theme.index.info',
        'sort' => 1,
        'icon-class' => 'icon-setting',
    ],

    /**
     * Configuration.
     */
    [
        'key' => 'configuration',
        'name' => 'admin::app.layouts.configuration',
        'route' => 'admin.configuration.index',
        'sort' => 9,
        'icon-class' => 'icon-configuration',
    ],
];
