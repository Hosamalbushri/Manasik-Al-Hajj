<?php

namespace Webkul\Web\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Webkul\Web\Models\WebDuaSection;

class WebDuaRepository
{
    /**
     * Active sections with active duas for storefront tabs (locale-aware labels).
     *
     * @return Collection<int, array{slug: string, tab_id: string, label: string, badge: string, duas: list<array{title: string, text: string, reference: string}>}>
     */
    public function getActiveSectionsWithDuasForLocale(?string $locale = null): Collection
    {
        if (! Schema::hasTable('web_dua_sections') || ! Schema::hasTable('web_duas')) {
            return collect();
        }

        $locale = strtolower((string) ($locale ?: app()->getLocale()));

        return WebDuaSection::query()
            ->where('status', true)
            ->with([
                'duas' => static fn ($q) => $q->where('status', true)->orderBy('sort_order')->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (WebDuaSection $section) use ($locale) {
                $sectionContent = is_array($section->content) ? $section->content : [];
                $sectionLabel = $this->resolveSectionTitle($sectionContent, $locale);
                $tabId = $this->slugToTabId((string) $section->slug);

                $duas = [];
                foreach ($section->duas as $dua) {
                    $d = $this->resolveDuaFields(is_array($dua->content) ? $dua->content : [], $locale);
                    if ($d['text'] === '' && $d['title'] === '') {
                        continue;
                    }
                    $duas[] = [
                        'title'     => $d['title'] !== '' ? $d['title'] : $sectionLabel,
                        'text'      => $d['text'],
                        'reference' => $d['reference'],
                    ];
                }

                return [
                    'slug'   => (string) $section->slug,
                    'tab_id' => $tabId,
                    'label'  => $sectionLabel !== '' ? $sectionLabel : $section->slug,
                    'badge'  => $sectionLabel !== '' ? $sectionLabel : (string) $section->slug,
                    'duas'   => $duas,
                ];
            })
            ->filter(static fn (array $row): bool => $row['duas'] !== [])
            ->values();
    }

    public function slugToTabId(string $slug): string
    {
        $s = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $slug) ?? $slug;
        $s = trim((string) $s, '-');

        return $s !== '' ? $s : 'section';
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public function resolveSectionTitle(array $content, string $locale): string
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

        return mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500);
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array{title: string, text: string, reference: string}
     */
    public function resolveDuaFields(array $content, string $locale): array
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

        return [
            'title'     => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
            'text'      => mb_substr(trim((string) ($slice['text'] ?? '')), 0, 10000),
            'reference' => mb_substr(trim((string) ($slice['reference'] ?? '')), 0, 1000),
        ];
    }
}
