<?php

namespace Webkul\Manasik\Repositories;

use Illuminate\Support\Facades\Schema;
use Webkul\Manasik\Models\HajjRite;

class HajjRiteRepository
{
    public function __construct(
        protected DuaRepository $duaRepository
    ) {}

    /**
     * @param  array<string, mixed>  $content
     * @return array{tab_label: string, title: string, subtitle: string, badge: string, description: string, info_items: list<array{text: string}>}
     */
    public function resolveRiteContent(array $content, string $locale): array
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

        $infoItems = $slice['info_items'] ?? [];
        if (! is_array($infoItems)) {
            $infoItems = [];
        }

        $normalizedItems = [];
        foreach ($infoItems as $row) {
            if (! is_array($row)) {
                continue;
            }
            $text = mb_substr(trim((string) ($row['text'] ?? '')), 0, 2000);
            if ($text === '') {
                continue;
            }
            $normalizedItems[] = ['text' => $text];
        }

        return [
            'tab_label' => mb_substr(trim((string) ($slice['tab_label'] ?? '')), 0, 500),
            'title' => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
            'subtitle' => mb_substr(trim((string) ($slice['subtitle'] ?? '')), 0, 500),
            'badge' => mb_substr(trim((string) ($slice['badge'] ?? '')), 0, 200),
            'description' => mb_substr(trim((string) ($slice['description'] ?? '')), 0, 50000),
            'info_items' => $normalizedItems,
        ];
    }

    /**
     * Guide steps for storefront: same shape as legacy lang `manasik.steps` plus `duas` list.
     *
     * @return list<array<string, mixed>>
     */
    public function getGuideStepsForLocale(?string $locale = null): array
    {
        if (! Schema::hasTable('manasik_hajj_rites') || ! Schema::hasTable('manasik_hajj_rite_dua')) {
            return [];
        }

        $locale = strtolower((string) ($locale ?: app()->getLocale()));

        $rites = HajjRite::query()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with([
                'duas' => static fn ($q) => $q->where('status', true)
                    ->orderByPivot('sort_order')
                    ->orderByPivot('id'),
            ])
            ->get();

        if ($rites->isEmpty()) {
            return [];
        }

        $out = [];

        foreach ($rites as $rite) {
            $content = is_array($rite->content) ? $rite->content : [];
            $base = $this->resolveRiteContent($content, $locale);

            $duas = [];
            foreach ($rite->duas as $dua) {
                $d = $this->duaRepository->resolveDuaFields(
                    is_array($dua->content) ? $dua->content : [],
                    $locale
                );
                if ($d['text'] === '' && $d['title'] === '') {
                    continue;
                }
                $duas[] = [
                    'dua_label' => $d['title'],
                    'dua_text' => $d['text'],
                    'dua_reference' => $d['reference'],
                ];
            }

            $row = $base;
            $row['duas'] = $duas;

            if ($duas !== []) {
                $row['dua_label'] = $duas[0]['dua_label'];
                $row['dua_text'] = $duas[0]['dua_text'];
                $row['dua_reference'] = $duas[0]['dua_reference'];
            } else {
                $row['dua_label'] = '';
                $row['dua_text'] = '';
                $row['dua_reference'] = '';
            }

            $out[] = $row;
        }

        return $out;
    }

    public function getGuideStepCount(?string $locale = null): int
    {
        $steps = $this->getGuideStepsForLocale($locale);
        if ($steps === []) {
            $steps = trans('web::app.manasik.steps');
            $steps = is_array($steps) ? $steps : [];
        }

        return count($steps);
    }
}
