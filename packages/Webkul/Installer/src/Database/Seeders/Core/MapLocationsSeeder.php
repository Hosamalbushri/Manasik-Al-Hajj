<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Webkul\Installer\Support\WebThemeInstallAssets;

/**
 * Seeds `manasik_map_locations` from `config('web.maps.cards')` on first install.
 * Lives in the Installer package so the install seeder chain does not depend on Manasik autoload order.
 */
class MapLocationsSeeder extends Seeder
{
    /**
     * Seed map locations from config when the table is empty (first install / manual run).
     */
    public function run(): void
    {
        if (! Schema::hasTable('manasik_map_locations')) {
            return;
        }

        WebThemeInstallAssets::ensureMakaPublishedToPublicDisk();

        if (DB::table('manasik_map_locations')->exists()) {
            return;
        }

        $cards = config('web.maps.cards', []);
        if (! is_array($cards) || $cards === []) {
            return;
        }

        $defaultLocale = strtolower((string) config('app.locale', 'en'));
        $now = now();

        foreach ($cards as $i => $row) {
            if (! is_array($row) || empty($row['slug'])) {
                continue;
            }

            $slug = (string) $row['slug'];
            $base = 'web::app.maps.cards.'.$slug;

            $featuresEn = Lang::get($base.'.features', [], 'en');
            $featuresEn = is_array($featuresEn) ? array_values($featuresEn) : [];

            $featuresAr = Lang::get($base.'.features', [], 'ar');
            $featuresAr = is_array($featuresAr) ? array_values($featuresAr) : [];

            $content = [
                'default_locale' => $defaultLocale,
                'translations' => [
                    'en' => [
                        'title' => (string) Lang::get($base.'.title', [], 'en'),
                        'badge' => (string) Lang::get($base.'.badge', [], 'en'),
                        'description' => (string) Lang::get($base.'.description', [], 'en'),
                        'detail_alert' => (string) Lang::get($base.'.detail_alert', [], 'en'),
                        'image_alt' => (string) Lang::get($base.'.image_alt', [], 'en'),
                        'features' => $featuresEn,
                    ],
                    'ar' => [
                        'title' => (string) Lang::get($base.'.title', [], 'ar'),
                        'badge' => (string) Lang::get($base.'.badge', [], 'ar'),
                        'description' => (string) Lang::get($base.'.description', [], 'ar'),
                        'detail_alert' => (string) Lang::get($base.'.detail_alert', [], 'ar'),
                        'image_alt' => (string) Lang::get($base.'.image_alt', [], 'ar'),
                        'features' => $featuresAr !== [] ? $featuresAr : $featuresEn,
                    ],
                ],
            ];

            $insert = [
                'slug' => $slug,
                'map_id' => (string) ($row['map_id'] ?? 'web-map-'.$slug),
                'image' => (string) ($row['image'] ?? ''),
                'embed' => (string) ($row['embed'] ?? ''),
                'sort_order' => (int) $i,
                'status' => true,
                'content' => json_encode($content),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (Schema::hasColumn('manasik_map_locations', 'latitude')) {
                $lat = $row['latitude'] ?? null;
                $lng = $row['longitude'] ?? null;
                $insert['latitude'] = $lat !== null && $lat !== '' ? (float) $lat : null;
                $insert['longitude'] = $lng !== null && $lng !== '' ? (float) $lng : null;
                $insert['zoom'] = isset($row['zoom']) ? max(1, min(21, (int) $row['zoom'])) : 15;
            }

            DB::table('manasik_map_locations')->insert($insert);
        }
    }
}
