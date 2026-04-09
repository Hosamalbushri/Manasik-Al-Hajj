<?php

/**
 * Used when astrotomic/laravel-translatable is installed. Locales are synced from
 * the `locales` table on boot via CoreServiceProvider when the table exists.
 */
return [
    'locales' => ['en'],

    'locale' => null,

    'use_fallback' => true,

    'use_property_fallback' => true,

    'fallback_locale' => 'en',

    'locale_key' => 'locale',

    'locale_to_country' => [],
];
