<?php

namespace Webkul\Web\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Storefront branding from Core configuration (general.store.web.*),
 * used as fallback when the web theme customizer has no logo/favicon.
 */
final class WebCoreStoreBranding
{
    public static function storefrontLogoStoragePath(): string
    {
        $path = core()->getConfigData('general.store.web.logo_image')
            ?: core()->getConfigData('general.store.shop.logo_image')
            ?: core()->getConfigData('general.design.shop.logo_image');

        return is_string($path) ? trim($path) : '';
    }

    public static function storefrontLogoUrl(): string
    {
        $path = self::storefrontLogoStoragePath();

        return $path !== '' ? Storage::url($path) : '';
    }

    public static function storefrontFaviconStoragePath(): string
    {
        $path = core()->getConfigData('general.store.web.favicon')
            ?: core()->getConfigData('general.store.shop.favicon')
            ?: core()->getConfigData('general.design.shop.favicon');

        return is_string($path) ? trim($path) : '';
    }
}
