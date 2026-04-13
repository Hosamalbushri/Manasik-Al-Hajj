<?php

namespace Webkul\Installer\Support;

use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;

/**
 * Publishes Web storefront images from installer package assets during seeding.
 *
 * Mirrors Bagisto's {@see \Webkul\Installer\Database\Seeders\Shop\ThemeCustomizationTableSeeder::storeFileIfExists}:
 * files live under {@see SEEDER_IMAGES_BASE}, then {@see Storage::putFile} / {@see \Illuminate\Contracts\Filesystem\Filesystem::putFileAs}
 * onto the default public disk.
 */
class WebThemeInstallAssets
{
    /**
     * Project-relative path to bundled seed images (place {@see MAKA_FILENAME} here).
     */
    public const SEEDER_IMAGES_BASE = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/web/';

    public const MAKA_FILENAME = 'Maka.jpg';

    /**
     * Path relative to the `public` storage disk root (persisted in theme options + map rows).
     */
    public const MAKA_PUBLIC_RELATIVE_PATH = 'theme/web-install/'.self::MAKA_FILENAME;

    public static function seederPackageMakaPath(): string
    {
        return base_path(self::SEEDER_IMAGES_BASE.self::MAKA_FILENAME);
    }

    /**
     * Optional fallback when the installer asset is absent (e.g. older checkouts).
     */
    public static function webPackageMakaFallbackPath(): string
    {
        return base_path('packages/Webkul/Web/src/Resources/assets/images/'.self::MAKA_FILENAME);
    }

    public static function resolveMakaSourcePath(): ?string
    {
        $primary = self::seederPackageMakaPath();
        if (is_file($primary)) {
            return $primary;
        }

        $fallback = self::webPackageMakaFallbackPath();

        return is_file($fallback) ? $fallback : null;
    }

    /**
     * Publish Maka.jpg to public storage (idempotent), Bagisto-style upload from package disk.
     */
    public static function ensureMakaPublishedToPublicDisk(): void
    {
        $disk = Storage::disk('public');
        $relative = self::MAKA_PUBLIC_RELATIVE_PATH;

        if ($disk->exists($relative)) {
            return;
        }

        $src = self::resolveMakaSourcePath();
        if ($src === null) {
            return;
        }

        $dir = dirname($relative);
        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $disk->putFileAs($dir, new HttpFile($src), self::MAKA_FILENAME);
    }
}
