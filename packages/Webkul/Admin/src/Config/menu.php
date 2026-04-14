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
     * Hajj rites guide (/manasik) — core manasik content.
     */
    [
        'key' => 'hajj_rites',
        'name' => 'admin::app.settings.hajj-rites.menu-title',
        'route' => 'admin.manasik-hajj-rites.index',
        'sort' => 2,
        'icon-class' => 'icon-activity',
    ],

    /**
     * Dhikr & duas: one parent menu with sections + duas children.
     */
    [
        'key' => 'adhkar_duas',
        'name' => 'admin::app.settings.adhkar-duas.menu-title',
        'route' => 'admin.adhkar-duas.dua-sections.index',
        'sort' => 3,
        'icon-class' => 'icon-bookmark',
    ], [
        'key' => 'adhkar_duas.dua_sections',
        'name' => 'admin::app.settings.adhkar-duas.menu-sections',
        'route' => 'admin.adhkar-duas.dua-sections.index',
        'sort' => 0,
        'icon-class' => 'icon-folder',
    ], [
        'key' => 'adhkar_duas.duas',
        'name' => 'admin::app.settings.adhkar-duas.menu-duas',
        'route' => 'admin.adhkar-duas.duas.index',
        'sort' => 1,
        'icon-class' => 'icon-note',
    ],

    /**
     * Map locations (top-level sidebar; permissions map_locations.*).
     */
    [
        'key' => 'map_locations',
        'name' => 'admin::app.settings.map-locations.menu-title',
        'route' => 'admin.map-locations.index',
        'sort' => 4,
        'icon-class' => 'icon-location',
    ],

    /**
     * Hajj pilgrims (registered portal accounts).
     */
    [
        'key' => 'hajj_pilgrims',
        'name' => 'admin::app.settings.hajj-pilgrims.menu-title',
        'route' => 'admin.manasik-hajj-users.index',
        'sort' => 5,
        'icon-class' => 'icon-contact',
    ],

    /**
     * Settings.
     */
    [
        'key' => 'settings',
        'name' => 'admin::app.layouts.settings',
        'route' => 'admin.settings.index',
        'sort' => 6,
        'icon-class' => 'icon-setting',
    ], [
        'key' => 'settings.locales',
        'name' => 'admin::app.layouts.locales',
        'route' => 'admin.settings.locales.index',
        'info' => 'admin::app.layouts.locales-info',
        'sort' => 0,
        'icon-class' => 'icon-language',
    ], [
        'key' => 'settings.locales.catalog',
        'name' => 'admin::app.settings.locales.tabs.catalog',
        'route' => 'admin.settings.locales.index',
        'info' => 'admin::app.settings.locales.tabs.catalog-info',
        'sort' => 1,
        'icon-class' => 'icon-language',
    ], [
        'key' => 'settings.locales.website',
        'name' => 'admin::app.settings.locales.tabs.website',
        'route' => 'admin.settings.locales.website',
        'info' => 'admin::app.settings.locales.tabs.website-info',
        'sort' => 2,
        'icon-class' => 'icon-language',
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
        'icon-class' => 'icon-image',
    ], [
        'key' => 'settings.web_theme.homepage',
        'name' => 'admin::app.settings.web-theme.index.title',
        'route' => 'admin.settings.web-theme.index',
        'info' => 'admin::app.settings.web-theme.index.info',
        'sort' => 1,
        'icon-class' => 'icon-image',
    ],

    /**
     * Configuration.
     */
    [
        'key' => 'configuration',
        'name' => 'admin::app.layouts.configuration',
        'route' => 'admin.configuration.index',
        'sort' => 7,
        'icon-class' => 'icon-configuration',
    ],
];
