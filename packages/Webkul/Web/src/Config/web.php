<?php

use Webkul\Installer\Support\WebThemeInstallAssets;

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
     * Default full-bleed band gradient (matches prayer-times CSS fallbacks). Overridden by
     * general.store.web.inner_page_hero_gradient_* and section_divider_gradient_* in core_config.
     *
     * @var array{gradient_from: string, gradient_mid: string, gradient_to: string, gold?: string}
     */
    'band_background' => [
        'gradient_from' => '#0D2A1A',
        'gradient_mid' => '#1A3A2A',
        'gradient_to' => '#0D2A1A',
        'gold' => '#D4AF37',
    ],

    /**
     * Brand colors when admin / core_config has no store palette yet (also .env overrides).
     * When configured in admin (General → Store → Web), those values drive --shop-* on the layout.
     */
    'identity' => [
        'fallback_shop_colors' => [
            'primary' => env('WEB_FALLBACK_PRIMARY', '#165022'),
            'accent' => env('WEB_FALLBACK_ACCENT', '#2E8B3A'),
            'icon' => env('WEB_FALLBACK_ICON', '#A67C2A'),
            'badge' => env('WEB_FALLBACK_BADGE', '#D4AF37'),
        ],

        /**
         * Optional hex overrides for maps / adhkar / shared empty state (must be #RGB or #RRGGBB).
         * Leave null to derive from --shop-primary and --shop-badge-color in CSS.
         *
         * WEB_IDENTITY_CARD_INK, WEB_IDENTITY_CARD_MUTED, WEB_IDENTITY_ACCENT_GOLD,
         * WEB_IDENTITY_ACCENT_GOLD_BRIGHT, WEB_IDENTITY_PARCHMENT_{START|MID|END}
         */
        'card_ink' => env('WEB_IDENTITY_CARD_INK'),
        'card_muted' => env('WEB_IDENTITY_CARD_MUTED'),
        'accent_gold' => env('WEB_IDENTITY_ACCENT_GOLD'),
        'accent_gold_bright' => env('WEB_IDENTITY_ACCENT_GOLD_BRIGHT'),
        'parchment_start' => env('WEB_IDENTITY_PARCHMENT_START'),
        'parchment_mid' => env('WEB_IDENTITY_PARCHMENT_MID'),
        'parchment_end' => env('WEB_IDENTITY_PARCHMENT_END'),
    ],

    /**
     * Home page SEO. Null values fall back to lang files.
     */
    'home_seo' => [
        'meta_title' => env('WEB_HOME_META_TITLE', env('SHOP_HOME_META_TITLE')),
        'meta_description' => env('WEB_HOME_META_DESCRIPTION', env('SHOP_HOME_META_DESCRIPTION')),
        'meta_keywords' => env('WEB_HOME_META_KEYWORDS', env('SHOP_HOME_META_KEYWORDS')),
    ],

    /**
     * Home page sections when DB has no rows (fallback only).
     * After installer seeding, rows come from shop_theme_customizations (see WebThemeDefaultsSeeder):
     * hero → prayer times (title/description in block) → supplications (title + 3 cards) → section divider (maps intro) → maps showcase (3 cards).
     */
    'home_customizations' => [
        [
            'sort_order' => 1,
            'status' => 1,
            'type' => 'immersive_hero',
            'options' => [],
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

    /**
     * Primary header tabs (fixed targets). Order in the admin is stored per locale; URLs are not editable.
     *
     * @var array<string, array{route: ?string, path: string}>
     */
    'header_primary_tabs' => [
        'home' => [
            'route' => 'web.home.index',
            'path' => '/',
        ],
        'services' => [
            'route' => null,
            'path' => '#services',
        ],
        'maps' => [
            'route' => 'web.maps.index',
            'path' => '/maps',
        ],
        'adhkar' => [
            'route' => 'web.adhkar.index',
            'path' => '/adhkar',
        ],
    ],

    /**
     * Maps page: location cards. Copy matches web::app.maps.cards.{slug}.* lang keys; embed/src URLs live here.
     *
     * google_maps_embed_api_key: optional. If set, location iframes use Google Maps Embed API (recommended).
     * Create a key in Google Cloud Console and enable "Maps Embed API".
     *
     * @var array{google_maps_embed_api_key: string, cards: list<array{slug: string, map_id: string, image: string, embed: string, latitude?: float, longitude?: float, zoom?: int}>}
     */
    'maps' => [
        'google_maps_embed_api_key' => env('GOOGLE_MAPS_EMBED_API_KEY', ''),

        'cards' => [
            [
                'slug' => 'makkah',
                'map_id' => 'web-map-makkah',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3705.210322245466!2d39.8245123!3d21.4225102!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b4e4c1e4b1d%3A0x1e5c4e2b6e2e4b1d!2sMasjid%20Al-Haram!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 21.4224779,
                'longitude' => 39.8261417,
                'zoom' => 17,
            ],
            [
                'slug' => 'mina',
                'map_id' => 'web-map-mina',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3705.5!2d39.8833!3d21.4167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b4e6e2e4b1d%3A0x2e5c4e2b6e2e4b1e!2sMina!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 21.4138,
                'longitude' => 39.8945,
                'zoom' => 14,
            ],
            [
                'slug' => 'arafat',
                'map_id' => 'web-map-arafat',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3704.8!2d39.8667!3d21.355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b2e2e2e4b1d%3A0x3e5c4e2b6e2e4b1f!2sMount%20Arafat!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 21.3555,
                'longitude' => 39.9838,
                'zoom' => 14,
            ],
            [
                'slug' => 'muzdalifah',
                'map_id' => 'web-map-muzdalifah',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3705.1!2d39.9!3d21.3833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b4e4e4e4b1d%3A0x4e5c4e2b6e2e4b20!2sMuzdalifah!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 21.3900,
                'longitude' => 39.9380,
                'zoom' => 14,
            ],
            [
                'slug' => 'jamarat',
                'map_id' => 'web-map-jamarat',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3705.3!2d39.8833!3d21.4167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b4e5e5e4b1d%3A0x5e5c4e2b6e2e4b21!2sJamarat!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 21.4175,
                'longitude' => 39.8762,
                'zoom' => 15,
            ],
            [
                'slug' => 'madinah',
                'map_id' => 'web-map-madinah',
                'image' => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                'embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3700.5!2d39.6!3d24.4667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c21b4f4f4f4b1d%3A0x6e5c4e2b6e2e4b22!2sAl-Masjid%20an-Nabawi!5e0!3m2!1sar!2ssa!4v1700000000000!5m2!1sar!2ssa',
                'latitude' => 24.4672131,
                'longitude' => 39.6115719,
                'zoom' => 17,
            ],
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
