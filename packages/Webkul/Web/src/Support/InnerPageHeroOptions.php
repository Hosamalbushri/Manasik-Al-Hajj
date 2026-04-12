<?php

namespace Webkul\Web\Support;

/**
 * Inner-page hero: global options (visibility, colours) + per primary-tab content in `pages`.
 * Legacy rows without `pages` use root-level content fields for all tab keys until re-saved.
 */
final class InnerPageHeroOptions
{
    /** @var list<string> */
    public const GLOBAL_KEYS = [
        'visible',
        'gradient_from',
        'gradient_mid',
        'gradient_to',
        'gold',
        'wave_fill',
    ];

    /** @var list<string> */
    public const CONTENT_KEYS = [
        'badge_show',
        'badge_icon',
        'badge_text',
        'title',
        'description',
        'primary_show',
        'primary_label',
        'primary_url',
        'primary_icon',
        'secondary_show',
        'secondary_label',
        'secondary_url',
        'secondary_icon',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        $bg = config('web.inner_page_hero.background', []);

        return [
            'visible'          => true,
            'badge_show'       => true,
            'badge_icon'       => '',
            'badge_text'       => '',
            'title'            => '',
            'description'      => '',
            'primary_show'     => true,
            'primary_label'    => '',
            'primary_url'      => '',
            'primary_icon'     => 'fas fa-play-circle',
            'secondary_show'   => true,
            'secondary_label'  => '',
            'secondary_url'    => '',
            'secondary_icon'   => 'fas fa-video',
            'gradient_from'    => (string) ($bg['gradient_from'] ?? '#0d2a1a'),
            'gradient_mid'     => (string) ($bg['gradient_mid'] ?? '#1a3a2a'),
            'gradient_to'      => (string) ($bg['gradient_to'] ?? '#0d2a1a'),
            'gold'             => (string) ($bg['gold'] ?? '#d4af37'),
            'wave_fill'        => (string) ($bg['wave_fill'] ?? '#fefaf5'),
            'breadcrumb'       => [],
            'pages'            => [],
        ];
    }

    /**
     * Resolved hero options for one primary tab (maps, services, …) and current locale.
     *
     * @param  array<string, mixed>  $localeRow  Localized options from inner_page_hero row.
     * @param  array<string, mixed>  $headerOpts  Localized web_header options (for labels + auto breadcrumb).
     * @param  array<string, mixed>  $overrides  Optional view/controller overrides (same as before).
     * @return array<string, mixed>
     */
    public static function resolveForPage(
        array $localeRow,
        ?string $pageKey,
        array $headerOpts,
        array $overrides = []
    ): array {
        $pageKey = $pageKey !== null ? trim($pageKey) : '';

        $o = self::defaults();

        foreach (self::GLOBAL_KEYS as $k) {
            if (array_key_exists($k, $localeRow)) {
                $o[$k] = $localeRow[$k];
            }
        }

        $pages = is_array($localeRow['pages'] ?? null) ? $localeRow['pages'] : [];
        $slice = ($pageKey !== '' && isset($pages[$pageKey]) && is_array($pages[$pageKey]))
            ? $pages[$pageKey]
            : [];

        if ($slice === [] && $pages === []) {
            foreach (self::CONTENT_KEYS as $k) {
                if (array_key_exists($k, $localeRow)) {
                    $slice[$k] = $localeRow[$k];
                }
            }
        }

        foreach (self::CONTENT_KEYS as $k) {
            if (array_key_exists($k, $slice)) {
                $o[$k] = $slice[$k];
            }
        }

        $o['breadcrumb'] = InnerPageHeroBreadcrumb::auto(
            $pageKey !== '' ? $pageKey : null,
            $headerOpts
        );

        return self::applyRuntimeOverrides($o, $overrides);
    }

    /**
     * @param  array<string, mixed>  $fromDb
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function merge(array $fromDb, array $overrides = []): array
    {
        return self::applyRuntimeOverrides(array_replace(self::defaults(), $fromDb), $overrides);
    }

    /**
     * @param  array<string, mixed>  $o
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private static function applyRuntimeOverrides(array $o, array $overrides): array
    {
        $stringKeys = ['title', 'description', 'badge_text', 'primary_label', 'primary_url', 'secondary_label', 'secondary_url'];
        foreach ($stringKeys as $k) {
            if (isset($overrides[$k]) && is_string($overrides[$k]) && trim($overrides[$k]) !== '') {
                $o[$k] = trim($overrides[$k]);
            }
        }

        $iconKeys = ['badge_icon', 'primary_icon', 'secondary_icon'];
        foreach ($iconKeys as $k) {
            if (isset($overrides[$k]) && is_string($overrides[$k]) && trim($overrides[$k]) !== '') {
                $o[$k] = trim($overrides[$k]);
            }
        }

        if (isset($overrides['breadcrumb']) && is_array($overrides['breadcrumb']) && $overrides['breadcrumb'] !== []) {
            $o['breadcrumb'] = $overrides['breadcrumb'];
        }

        foreach (['visible', 'badge_show', 'primary_show', 'secondary_show'] as $boolKey) {
            if (array_key_exists($boolKey, $overrides)) {
                $o[$boolKey] = filter_var($overrides[$boolKey], FILTER_VALIDATE_BOOLEAN);
            }
        }

        foreach (['gradient_from', 'gradient_mid', 'gradient_to', 'gold', 'wave_fill'] as $colorKey) {
            if (isset($overrides[$colorKey]) && is_string($overrides[$colorKey]) && trim($overrides[$colorKey]) !== '') {
                $o[$colorKey] = trim($overrides[$colorKey]);
            }
        }

        return $o;
    }
}
