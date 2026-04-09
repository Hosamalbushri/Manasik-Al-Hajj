<?php

return [

    /**
     * Theme code for Web portal rows in shop_theme_customizations (admin → Web theme).
     */
    'storefront_theme_code' => env('WEB_STOREFRONT_THEME_CODE', env('SHOP_STOREFRONT_THEME_CODE', 'web')),

    /**
     * Legacy Bagisto-style default theme code (admin may still use "default" rows).
     */
    'legacy_storefront_theme_code' => env('SHOP_STOREFRONT_THEME_CODE', 'default'),

    /**
     * Labels for theme_code in admin UI.
     */
    'theme_definitions' => [
        'default' => [
            'name' => 'Default',
        ],
        'web' => [
            'name' => 'Web portal',
        ],
    ],

    'logo_url' => null,

    /**
     * Home page SEO. Null values fall back to lang files.
     */
    'home_seo' => [
        'meta_title'       => env('WEB_HOME_META_TITLE', env('SHOP_HOME_META_TITLE')),
        'meta_description' => env('WEB_HOME_META_DESCRIPTION', env('SHOP_HOME_META_DESCRIPTION')),
        'meta_keywords'    => env('WEB_HOME_META_KEYWORDS', env('SHOP_HOME_META_KEYWORDS')),
    ],

    /**
     * Home page sections when DB has no rows (fallback).
     */
    'home_customizations' => [
        [
            'sort_order' => 1,
            'status'     => 1,
            'type'       => 'immersive_hero',
            'options'    => [],
        ],
    ],

    'footer_sections' => [
        [
            ['title' => 'web::app.home.links.home', 'route' => 'web.home.index'],
            ['title' => 'web::app.home.links.services', 'url' => '#services'],
        ],
        [
            ['title' => 'web::app.home.links.about', 'url' => '#'],
            ['title' => 'web::app.home.links.contact', 'url' => '#'],
        ],
        [
            ['title' => 'web::app.components.layouts.footer.link-student-login', 'student_login' => true],
        ],
    ],

    'services' => [
        [
            'service_icon' => 'icon-calendar',
            'title' => 'web::app.components.layouts.services.calendar-title',
            'description' => 'web::app.components.layouts.services.calendar-desc',
        ],
        [
            'service_icon' => 'icon-email',
            'title' => 'web::app.components.layouts.services.updates-title',
            'description' => 'web::app.components.layouts.services.updates-desc',
        ],
        [
            'service_icon' => 'icon-location',
            'title' => 'web::app.components.layouts.services.campus-title',
            'description' => 'web::app.components.layouts.services.campus-desc',
        ],
    ],
];
