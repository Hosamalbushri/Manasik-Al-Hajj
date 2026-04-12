<?php

namespace Webkul\Web\Support;

/**
 * Store-wide gradient stops for inner hero, section dividers, and prayer band fallbacks (General → Store → Web).
 */
final class WebStoreBandColors
{
    /**
     * @return array{from: string, mid: string, to: string}
     */
    public static function defaultTriple(): array
    {
        $b = config('web.band_background', []);

        return [
            'from' => self::hexOr($b['gradient_from'] ?? null, '#0D2A1A'),
            'mid'  => self::hexOr($b['gradient_mid'] ?? null, '#1A3A2A'),
            'to'   => self::hexOr($b['gradient_to'] ?? null, '#0D2A1A'),
        ];
    }

    /**
     * Resolved store defaults for inner hero (core_config → config/web band_background).
     *
     * @return array{from: string, mid: string, to: string}
     */
    public static function innerHeroStoreDefaults(): array
    {
        $d = self::defaultTriple();

        return [
            'from' => self::hexOr(core()->getConfigData('general.store.web.inner_page_hero_gradient_from'), $d['from']),
            'mid'  => self::hexOr(core()->getConfigData('general.store.web.inner_page_hero_gradient_mid'), $d['mid']),
            'to'   => self::hexOr(core()->getConfigData('general.store.web.inner_page_hero_gradient_to'), $d['to']),
        ];
    }

    /**
     * Resolved store defaults for home section dividers (core_config → config/web band_background).
     *
     * @return array{from: string, mid: string, to: string}
     */
    public static function sectionDividerStoreDefaults(): array
    {
        $d = self::defaultTriple();

        return [
            'from' => self::hexOr(core()->getConfigData('general.store.web.section_divider_gradient_from'), $d['from']),
            'mid'  => self::hexOr(core()->getConfigData('general.store.web.section_divider_gradient_mid'), $d['mid']),
            'to'   => self::hexOr(core()->getConfigData('general.store.web.section_divider_gradient_to'), $d['to']),
        ];
    }

    public static function hexOr(mixed $value, string $fallback): string
    {
        $v = strtoupper(trim((string) $value));

        return preg_match('/^#[0-9A-F]{6}$/', $v) ? $v : $fallback;
    }

    /**
     * Theme / options hex if valid, otherwise null.
     */
    public static function optionalHex(mixed $value): ?string
    {
        $v = strtoupper(trim((string) $value));

        return preg_match('/^#[0-9A-F]{6}$/', $v) ? $v : null;
    }
}
