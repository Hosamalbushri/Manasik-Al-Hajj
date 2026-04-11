<?php

namespace Webkul\Web\Support;

/**
 * Breadcrumb trail for the inner-pages hero: Home link + current primary tab, using the same
 * labels and URLs as the storefront header (nav_primary + web.header_primary_tabs).
 */
final class InnerPageHeroBreadcrumb
{
    /**
     * @param  array<string, mixed>  $headerLocalizedOptions  Resolved web_header options for current locale.
     * @return list<array{label: string, url: string}>
     */
    public static function auto(?string $pageKey, array $headerLocalizedOptions): array
    {
        $pageKey = $pageKey !== null ? trim($pageKey) : '';

        if ($pageKey === '' || $pageKey === 'home') {
            return [];
        }

        $definitions = WebHeaderPrimaryTabs::definitions();

        if (! isset($definitions[$pageKey])) {
            return [];
        }

        $homeLabel = self::labelForNavKey('home', $headerLocalizedOptions);
        $currentLabel = self::labelForNavKey($pageKey, $headerLocalizedOptions);

        $fallbacks = __('web::app.inner_hero.nav_fallback_labels');

        if ($homeLabel === '') {
            $homeLabel = is_array($fallbacks) ? (string) ($fallbacks['home'] ?? '') : '';
            if ($homeLabel === '') {
                $homeLabel = 'Home';
            }
        }

        if ($currentLabel === '') {
            $currentLabel = is_array($fallbacks) ? (string) ($fallbacks[$pageKey] ?? '') : '';
            if ($currentLabel === '') {
                $currentLabel = ucfirst(str_replace('_', ' ', $pageKey));
            }
        }

        return [
            ['label' => $homeLabel, 'url' => WebHeaderPrimaryTabs::resolveUrl('home')],
            ['label' => $currentLabel, 'url' => ''],
        ];
    }

    /**
     * @param  array<string, mixed>  $headerLocalizedOptions
     */
    private static function labelForNavKey(string $key, array $headerLocalizedOptions): string
    {
        $nav = is_array($headerLocalizedOptions['nav_primary'] ?? null)
            ? $headerLocalizedOptions['nav_primary']
            : [];

        foreach ($nav as $row) {
            if (! is_array($row)) {
                continue;
            }

            if ((string) ($row['page_key'] ?? '') === $key) {
                return trim((string) ($row['label'] ?? ''));
            }
        }

        return '';
    }
}
