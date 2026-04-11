<?php

namespace Webkul\Web\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Webkul\Core\Eloquent\Repository;
use Webkul\Web\Models\WebMapLocation;

class WebMapLocationRepository extends Repository
{
    /**
     * Neutral placeholder when no cover image is set (SVG, gray area).
     */
    public const PLACEHOLDER_IMAGE_DATA_URI = 'data:image/svg+xml,';

    public function model(): string
    {
        return WebMapLocation::class;
    }

    public function placeholderImageUrl(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750"><rect width="100%" height="100%" fill="#e5e7eb"/><rect x="1" y="1" width="1198" height="748" fill="none" stroke="#d1d5db" stroke-width="2"/></svg>';

        return self::PLACEHOLDER_IMAGE_DATA_URI.rawurlencode($svg);
    }

    /**
     * Active locations for the public maps page, ordered for display.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function getActiveCardsForLocale(?string $locale = null): Collection
    {
        if (! Schema::hasTable('web_map_locations')) {
            return collect();
        }

        $locale = strtolower((string) ($locale ?: app()->getLocale()));

        return $this->model
            ->newQuery()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (WebMapLocation $row) => $this->rowToCardArray($row, $locale));
    }

    /**
     * @return array<string, mixed>
     */
    public function rowToCardArray(WebMapLocation $row, string $locale): array
    {
        $slug = (string) $row->slug;

        $resolved = $this->resolveContentForLocale(is_array($row->content) ? $row->content : [], $locale);

        $image = trim((string) ($row->image ?? ''));
        if ($image !== '' && ! preg_match('#^https?://#i', $image)) {
            $image = \Illuminate\Support\Facades\Storage::url(ltrim(str_replace('storage/', '', $image), '/'));
        }
        if ($image === '') {
            $image = $this->placeholderImageUrl();
        }

        $embed = '';
        if ($row->latitude !== null && $row->longitude !== null) {
            $embed = $this->embedSrcFromLatLng(
                (float) $row->latitude,
                (float) $row->longitude,
                (int) ($row->zoom ?? 15)
            );
        }

        return [
            'slug'         => $slug,
            'map_id'       => (string) $row->map_id,
            'image'        => $image,
            'embed'        => $embed,
            'title'        => $resolved['title'],
            'badge'        => $resolved['badge'],
            'description'  => $resolved['description'],
            'features'     => $resolved['features'],
            'image_alt'    => '',
            'detail_alert' => $resolved['detail_alert'],
        ];
    }

    /**
     * @param  array<string, mixed>  $content  { default_locale?, translations?: array<string, array> }
     * @return array{title: string, badge: string, description: string, detail_alert: string, features: list<string>}
     */
    public function resolveContentForLocale(array $content, string $locale): array
    {
        $defaultLocale = strtolower((string) ($content['default_locale'] ?? config('app.locale', 'en')));
        $translations = $content['translations'] ?? null;
        if (! is_array($translations)) {
            $translations = [];
        }

        $slice = $translations[$locale] ?? $translations[$defaultLocale] ?? [];
        if (! is_array($slice)) {
            $slice = [];
        }

        $features = $slice['features'] ?? [];
        if (! is_array($features)) {
            $features = [];
        }
        $features = array_values(array_filter(array_map(
            static fn ($v): string => mb_substr(trim((string) $v), 0, 500),
            $features
        )));

        return [
            'title'        => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
            'badge'        => mb_substr(trim((string) ($slice['badge'] ?? '')), 0, 191),
            'description'  => mb_substr(trim((string) ($slice['description'] ?? '')), 0, 5000),
            'detail_alert' => mb_substr(trim((string) ($slice['detail_alert'] ?? '')), 0, 5000),
            'features'     => $features,
        ];
    }

    /**
     * Google Maps iframe src from coordinates.
     * With GOOGLE_MAPS_EMBED_API_KEY: Maps Embed API (view mode).
     * Without key: classic maps URL with output=embed (may be restricted by Google; API key recommended).
     */
    public function embedSrcFromLatLng(float $lat, float $lng, int $zoom = 15): string
    {
        $zoom = max(1, min(21, $zoom));
        $key = trim((string) config('web.maps.google_maps_embed_api_key', ''));

        if ($key !== '') {
            return 'https://www.google.com/maps/embed/v1/view?'.http_build_query([
                'key'     => $key,
                'center'  => sprintf('%F,%F', $lat, $lng),
                'zoom'    => $zoom,
                'maptype' => 'roadmap',
            ], '', '&', PHP_QUERY_RFC3986);
        }

        return 'https://www.google.com/maps?'.http_build_query([
            'q'      => sprintf('%F,%F', $lat, $lng),
            'z'      => $zoom,
            'output' => 'embed',
            'hl'     => strtolower((string) app()->getLocale()),
        ], '', '&', PHP_QUERY_RFC3986);
    }
}
