<?php

namespace Webkul\Manasik\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webkul\Manasik\Models\HajjRite;

class HajjRiteDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('manasik_hajj_rites')) {
            return;
        }

        if (HajjRite::query()->exists()) {
            return;
        }

        $codes = [];
        foreach (core()->storeLocales() as $row) {
            $c = strtolower((string) ($row['value'] ?? ''));
            if ($c !== '') {
                $codes[] = $c;
            }
        }
        if ($codes === []) {
            $codes = [strtolower((string) config('app.locale', 'en'))];
        }
        $codes = array_values(array_unique($codes));

        $byIndex = [];
        foreach ($codes as $code) {
            $steps = trans('web::app.manasik.steps', [], $code);
            if (! is_array($steps)) {
                continue;
            }
            foreach ($steps as $idx => $step) {
                if (! is_array($step)) {
                    continue;
                }
                $byIndex[(int) $idx][$code] = $step;
            }
        }
        ksort($byIndex, SORT_NUMERIC);

        if ($byIndex === []) {
            return;
        }

        $defaultLocale = $codes[0];

        $sort = 0;
        foreach ($byIndex as $i => $perLocale) {
            $translations = [];
            foreach ($codes as $code) {
                $step = $perLocale[$code] ?? null;
                if (! is_array($step)) {
                    $step = $perLocale[$defaultLocale] ?? reset($perLocale);
                }
                if (! is_array($step)) {
                    continue;
                }
                $items = $step['info_items'] ?? [];
                if (! is_array($items)) {
                    $items = [];
                }
                $normalizedItems = [];
                foreach ($items as $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $text = trim((string) ($row['text'] ?? ''));
                    if ($text === '') {
                        continue;
                    }
                    $normalizedItems[] = [
                        'text' => $text,
                    ];
                }
                $translations[$code] = [
                    'tab_label' => (string) ($step['tab_label'] ?? ''),
                    'title' => (string) ($step['title'] ?? ''),
                    'subtitle' => (string) ($step['subtitle'] ?? ''),
                    'badge' => (string) ($step['badge'] ?? ''),
                    'description' => (string) ($step['description'] ?? ''),
                    'info_items' => $normalizedItems,
                ];
            }

            $slugSource = $translations['en']['tab_label'] ?? $translations[$defaultLocale]['tab_label'] ?? '';
            $slug = Str::slug($slugSource);
            if ($slug === '') {
                $slug = 'hajj-rite-'.($i + 1);
            }
            $baseSlug = $slug;
            $n = 0;
            while (HajjRite::query()->where('slug', $slug)->exists()) {
                $n++;
                $slug = $baseSlug.'-'.$n;
            }

            HajjRite::query()->create([
                'slug' => $slug,
                'sort_order' => $sort,
                'status' => true,
                'content' => [
                    'default_locale' => $defaultLocale,
                    'translations' => $translations,
                ],
            ]);
            $sort++;
        }
    }
}
