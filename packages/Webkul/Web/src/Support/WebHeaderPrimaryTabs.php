<?php

namespace Webkul\Web\Support;

use Illuminate\Support\Facades\Route;

/**
 * Fixed storefront header primary tabs: URLs come from config, not the theme editor.
 */
final class WebHeaderPrimaryTabs
{
    /**
     * @return array<string, array{route: ?string, path: string}>
     */
    public static function definitions(): array
    {
        return config('web.header_primary_tabs', []);
    }

    /**
     * @return list<string>
     */
    public static function defaultKeyOrder(): array
    {
        return array_keys(self::definitions());
    }

    public static function resolveUrl(string $key): string
    {
        $defs = self::definitions();
        $tab = $defs[$key] ?? null;
        if (! is_array($tab)) {
            return '#';
        }
        $route = $tab['route'] ?? null;
        if (is_string($route) && $route !== '' && Route::has($route)) {
            return route($route);
        }

        return (string) ($tab['path'] ?? '#');
    }

    /**
     * @param  array<string, mixed>  $o  Localized header options
     * @param  list<array{label?: string, url?: string, icon?: string}>  $legacyNav
     * @return list<array{pageKey: string, label: string, slotTitle: string}>
     */
    public static function editorRowsFromOptions(array $o, array $legacyNav): array
    {
        $allowedOrder = self::defaultKeyOrder();
        if ($allowedOrder === []) {
            return [];
        }

        $labelsByKey = array_fill_keys($allowedOrder, '');
        $navPrimary = is_array($o['nav_primary'] ?? null) ? $o['nav_primary'] : [];

        foreach ($navPrimary as $r) {
            if (! is_array($r)) {
                continue;
            }
            $k = (string) ($r['page_key'] ?? '');
            if ($k !== '' && in_array($k, $allowedOrder, true)) {
                $labelsByKey[$k] = (string) ($r['label'] ?? '');
            }
        }

        $orderedKeys = [];
        foreach ($navPrimary as $r) {
            if (! is_array($r)) {
                continue;
            }
            $k = (string) ($r['page_key'] ?? '');
            if (in_array($k, $allowedOrder, true)) {
                $orderedKeys[] = $k;
            }
        }
        $orderedKeys = array_values(array_unique($orderedKeys));

        $hasLegacyLabelsOnly = $navPrimary !== []
            && isset($navPrimary[0])
            && is_array($navPrimary[0])
            && (! isset($navPrimary[0]['page_key']) || (string) $navPrimary[0]['page_key'] === '');

        if (count($orderedKeys) !== count($allowedOrder)) {
            $orderedKeys = $allowedOrder;
            if ($hasLegacyLabelsOnly) {
                foreach (range(0, 3) as $i) {
                    if (! isset($allowedOrder[$i])) {
                        break;
                    }
                    $k = $allowedOrder[$i];
                    $labelsByKey[$k] = (string) ($navPrimary[$i]['label'] ?? '');
                }
            } elseif ($navPrimary === []) {
                foreach (range(0, 3) as $i) {
                    if (! isset($allowedOrder[$i])) {
                        break;
                    }
                    $k = $allowedOrder[$i];
                    $labelsByKey[$k] = (string) ($legacyNav[$i]['label'] ?? '');
                }
            }
        }

        $rows = [];
        foreach ($orderedKeys as $k) {
            if (! in_array($k, $allowedOrder, true)) {
                continue;
            }
            $rows[] = [
                'pageKey'   => $k,
                'label'     => $labelsByKey[$k] ?? '',
                'slotTitle' => trans('admin::app.settings.web-theme.edit.web-nav-primary-slot-'.$k),
            ];
        }

        $present = array_column($rows, 'pageKey');
        foreach ($allowedOrder as $k) {
            if (! in_array($k, $present, true)) {
                $rows[] = [
                    'pageKey'   => $k,
                    'label'     => $labelsByKey[$k] ?? '',
                    'slotTitle' => trans('admin::app.settings.web-theme.edit.web-nav-primary-slot-'.$k),
                ];
            }
        }

        return array_slice($rows, 0, count($allowedOrder));
    }
}
