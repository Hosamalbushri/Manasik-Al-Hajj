<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\WebThemeCustomizationDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Web\Models\ThemeCustomization;
use Webkul\Web\Models\WebThemeCustomization;
use Webkul\Web\Repositories\WebThemeCustomizationRepository;
use Webkul\Web\Support\WebHeaderPrimaryTabs;

class WebThemeCustomizationController extends Controller
{
    /**
     * Types allowed when creating a new section (product carousel excluded for this portal).
     *
     * @var list<string>
     */
    private const TYPES_CREATABLE = [
        'image_carousel',
        'static_content',
        'immersive_hero',
        ThemeCustomization::SUPPLICATIONS_CONTENT,
        ThemeCustomization::SECTION_DIVIDER,
        ThemeCustomization::MAPS_SHOWCASE,
        ThemeCustomization::PRAYER_TIMES,
        ThemeCustomization::WEB_HEADER,
        ThemeCustomization::WEB_FOOTER,
        ThemeCustomization::INNER_PAGE_HERO,
    ];

    /**
     * Types allowed on update.
     *
     * @return list<string>
     */
    private static function typesForUpdate(): array
    {
        return self::TYPES_CREATABLE;
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeImmersiveHeroOptions(Request $request, WebThemeCustomization $theme, string $locale): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];
        $existingLocale = $this->localizedOptionsForLocale($theme, $locale);
        $existingSlides = is_array($existingLocale['slides'] ?? null) ? $existingLocale['slides'] : [];
        $deletedSlides = $request->input('deleted_slides');
        $deletedSlides = is_array($deletedSlides) ? $deletedSlides : [];

        foreach ($deletedSlides as $item) {
            $path = (string) ($item['image'] ?? '');
            if ($path === '') {
                continue;
            }

            $rel = ltrim(str_replace('storage/', '', $path), '/');
            if ($rel !== '') {
                Storage::disk('public')->delete($rel);
            }
        }

        $slides = [];

        $rawSlides = is_array($o['slides'] ?? null) ? $o['slides'] : [];
        ksort($rawSlides);

        foreach ($rawSlides as $i => $rawSlide) {
            if (! is_array($rawSlide)) {
                continue;
            }

            $title = mb_substr(trim((string) data_get($o, "slides.$i.title", '')), 0, 191);
            $description = mb_substr(trim((string) data_get($o, "slides.$i.description", '')), 0, 2000);
            $file = $request->file('options.slides.'.$i.'.image');
            $imagePath = mb_substr(trim((string) data_get($o, "slides.$i.image_path", data_get($o, "slides.$i.image", ''))), 0, 2048);
            $image = '';

            if ($file instanceof UploadedFile) {
                $image = $file->store("web-theme/{$theme->id}", 'public');
            } elseif ($imagePath !== '') {
                $image = str_replace('storage/', '', ltrim($imagePath, '/'));
            } elseif (isset($existingSlides[$i]['image']) && is_string($existingSlides[$i]['image'])) {
                $image = (string) $existingSlides[$i]['image'];
            }

            if ($title === '' && $description === '' && $image === '') {
                continue;
            }

            $stats = [];
            foreach (range(0, 2) as $j) {
                $number = mb_substr(trim((string) data_get($o, "slides.$i.stats.$j.number", '')), 0, 80);
                $label = mb_substr(trim((string) data_get($o, "slides.$i.stats.$j.label", '')), 0, 120);
                if ($number === '' && $label === '') {
                    continue;
                }
                $stats[] = ['number' => $number, 'label' => $label];
            }

            $slides[] = [
                'badge_icon'  => $this->sanitizeImmersiveFaClass((string) data_get($o, "slides.$i.badge_icon", 'fas fa-kaaba')),
                'badge'       => mb_substr(trim((string) data_get($o, "slides.$i.badge", '')), 0, 191),
                'title'       => $title,
                'description' => $description,
                'image'       => $image,
                'primary'     => [
                    'label' => mb_substr(trim((string) data_get($o, "slides.$i.primary.label", '')), 0, 191),
                    'icon'  => $this->sanitizeImmersiveFaClass((string) data_get($o, "slides.$i.primary.icon", '')),
                    'url'   => $this->sanitizeImmersiveUrl((string) data_get($o, "slides.$i.primary.url", '')),
                ],
                'secondary'   => [
                    'label' => mb_substr(trim((string) data_get($o, "slides.$i.secondary.label", '')), 0, 191),
                    'icon'  => $this->sanitizeImmersiveFaClass((string) data_get($o, "slides.$i.secondary.icon", '')),
                    'url'   => $this->sanitizeImmersiveUrl((string) data_get($o, "slides.$i.secondary.url", '')),
                ],
                'stats'       => $stats,
            ];
        }

        return [
            'slides' => $slides,
        ];
    }

    /**
     * Homepage supplications block: copy comes from Web duas (adhkar); theme only sets framing + limit + CTA.
     *
     * @return array{heading: string, subheading: string, limit: int, show_more: bool, more_url: string}
     */
    protected function normalizeSupplicationsOptions(Request $request): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];
        $heading = mb_substr(trim((string) ($o['heading'] ?? '')), 0, 191);
        $subheading = mb_substr(trim((string) ($o['subheading'] ?? '')), 0, 500);
        $limit = (int) ($o['limit'] ?? 6);
        $limit = max(1, min(50, $limit));
        $showMore = $request->boolean('options.show_more');
        $moreUrl = $this->sanitizeImmersiveUrl((string) ($o['more_url'] ?? ''));

        return [
            'heading'    => $heading,
            'subheading' => $subheading,
            'limit'      => $limit,
            'show_more'  => $showMore,
            'more_url'   => mb_substr($moreUrl, 0, 2048),
        ];
    }

    /**
     * Homepage maps block: same cards as the maps page; theme sets heading, optional limit, and link to full maps page.
     *
     * @return array{heading: string, subheading: string, limit: int, link_show: bool, link_label: string}
     */
    protected function normalizeMapsShowcaseOptions(Request $request): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];
        $heading = mb_substr(trim((string) ($o['heading'] ?? '')), 0, 191);
        $subheading = mb_substr(trim((string) ($o['subheading'] ?? '')), 0, 500);
        $limit = (int) ($o['limit'] ?? 0);
        $limit = max(0, min(50, $limit));
        $linkShow = $request->boolean('options.link_show');
        $linkLabel = mb_substr(trim((string) ($o['link_label'] ?? '')), 0, 191);

        return [
            'heading'    => $heading,
            'subheading' => $subheading,
            'limit'      => $limit,
            'link_show'  => $linkShow,
            'link_label' => $linkLabel,
        ];
    }

    /**
     * Homepage prayer times (client fetch: custom API URL or Aladhan timingsByCity fallback).
     *
     * @return array<string, mixed>
     */
    protected function normalizePrayerTimesOptions(Request $request): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];
        $heading = mb_substr(trim((string) ($o['heading'] ?? '')), 0, 191);
        $description = mb_substr(trim(strip_tags((string) ($o['description'] ?? ''))), 0, 500);
        $locationLabel = mb_substr(trim((string) ($o['location_label'] ?? '')), 0, 191);
        $apiUrl = $this->sanitizePrayerTimesApiUrl((string) ($o['api_url'] ?? ''));
        $city = $this->normalizePrayerTimesCityCountry((string) ($o['city'] ?? ''), 'Makkah');
        $country = $this->normalizePrayerTimesCityCountry((string) ($o['country'] ?? ''), 'Saudi Arabia');
        $method = (int) ($o['method'] ?? 2);
        $method = max(0, min(15, $method));
        $autoplayMs = (int) ($o['autoplay_ms'] ?? 4000);
        $autoplayMs = max(1000, min(60000, $autoplayMs));
        $hour12Raw = $o['hour12'] ?? null;
        if (is_array($hour12Raw)) {
            $hour12Raw = end($hour12Raw);
        }
        $hour12 = $hour12Raw === null || $hour12Raw === ''
            ? true
            : filter_var($hour12Raw, FILTER_VALIDATE_BOOLEAN);

        return [
            'api_url'         => $apiUrl,
            'heading'         => $heading,
            'description'     => $description,
            'location_label'  => $locationLabel,
            'city'            => $city,
            'country'         => $country,
            'method'          => $method,
            'autoplay_ms'     => $autoplayMs,
            'hour12'          => $hour12,
        ];
    }

    protected function sanitizePrayerTimesApiUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '' || strlen($url) > 2000) {
            return '';
        }
        if (! preg_match('#^https?://#i', $url)) {
            return '';
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return '';
        }

        return $url;
    }

    protected function normalizePrayerTimesCityCountry(string $value, string $fallback): string
    {
        $value = trim($value);
        $value = preg_replace('/[^\p{L}\p{N}\s\-\'’`]+/u', '', $value) ?? '';
        $value = mb_substr($value, 0, 80);

        return $value !== '' ? $value : $fallback;
    }

    protected function sanitizeImmersiveColor(string $value, string $fallback, bool $hexOnly): string
    {
        $value = trim($value);
        if ($value === '') {
            return $fallback;
        }
        if (strlen($value) > 120) {
            return $fallback;
        }
        if ($hexOnly) {
            return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) ? $value : $fallback;
        }
        if (preg_match('/^#[0-9A-Fa-f]{3,8}$/', $value)) {
            return $value;
        }
        if (preg_match('/^rgba?\([^)]+\)$/i', $value)) {
            return $value;
        }

        return $fallback;
    }

    protected function sanitizeImmersiveUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (strlen($url) > 2048) {
            return '';
        }
        if (str_starts_with($url, '/') || str_starts_with($url, '#') || str_starts_with($url, '?')) {
            return $url;
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return '';
    }

    protected function sanitizeImmersiveFaClass(string $class): string
    {
        $class = trim(preg_replace('/\s+/', ' ', $class) ?? '');
        if ($class === '') {
            return '';
        }
        if (strlen($class) > 80) {
            return '';
        }
        if (! preg_match('/^[a-z0-9\s\-]+$/i', $class)) {
            return '';
        }

        return $class;
    }

    public function __construct(
        protected WebThemeCustomizationRepository $webThemeCustomizationRepository
    ) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(WebThemeCustomizationDataGrid::class)->process();
        }

        return view('admin::settings.web-theme.index');
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->filled('id') && $request->hasFile('image')) {
            return $this->storeStaticEditorImage($request);
        }

        $themeCode = (string) config('web.storefront_theme_code', 'web');

        $this->validate($request, [
            'name'       => 'required|string|max:191',
            'sort_order' => 'required|integer|min:0',
            'type'       => 'required|in:'.implode(',', self::TYPES_CREATABLE),
        ]);

        if ($request->input('type') === ThemeCustomization::WEB_HEADER) {
            $exists = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::WEB_HEADER)
                ->exists();
            if ($exists) {
                return new JsonResponse([
                    'message' => trans('admin::app.settings.web-theme.create.web-header-duplicate'),
                    'errors'  => ['type' => [trans('admin::app.settings.web-theme.create.web-header-duplicate')]],
                ], 422);
            }
        }

        if ($request->input('type') === ThemeCustomization::WEB_FOOTER) {
            $exists = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::WEB_FOOTER)
                ->exists();
            if ($exists) {
                return new JsonResponse([
                    'message' => trans('admin::app.settings.web-theme.create.web-footer-duplicate'),
                    'errors'  => ['type' => [trans('admin::app.settings.web-theme.create.web-footer-duplicate')]],
                ], 422);
            }
        }

        if ($request->input('type') === ThemeCustomization::IMMERSIVE_HERO) {
            $exists = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::IMMERSIVE_HERO)
                ->exists();
            if ($exists) {
                return new JsonResponse([
                    'message' => trans('admin::app.settings.web-theme.create.immersive-hero-duplicate'),
                    'errors'  => ['type' => [trans('admin::app.settings.web-theme.create.immersive-hero-duplicate')]],
                ], 422);
            }
        }

        if ($request->input('type') === ThemeCustomization::INNER_PAGE_HERO) {
            $exists = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::INNER_PAGE_HERO)
                ->exists();
            if ($exists) {
                return new JsonResponse([
                    'message' => trans('admin::app.settings.web-theme.create.inner-page-hero-duplicate'),
                    'errors'  => ['type' => [trans('admin::app.settings.web-theme.create.inner-page-hero-duplicate')]],
                ], 422);
            }
        }

        $theme = $this->webThemeCustomizationRepository->create([
            'name'       => $request->input('name'),
            'sort_order' => (int) $request->input('sort_order'),
            'type'       => $request->input('type'),
            'theme_code' => $themeCode,
            'status'     => false,
            'options'    => [],
        ]);

        return new JsonResponse([
            'redirect_url' => route('admin.settings.web-theme.edit', $theme->id),
        ]);
    }

    public function edit(int $id): View
    {
        $theme = $this->webThemeCustomizationRepository->findOrFail($id);
        $storeLocaleCodes = $this->getStoreLocaleCodes();
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);
        $opts = $this->localizedOptionsForLocale($theme, $activeLocale);

        $innerHeroHeaderOpts = [];
        if ($theme->type === ThemeCustomization::INNER_PAGE_HERO) {
            $themeCode = (string) config('web.storefront_theme_code', 'web');
            $headerTheme = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::WEB_HEADER)
                ->orderBy('id')
                ->first();
            if ($headerTheme) {
                $innerHeroHeaderOpts = $this->localizedOptionsForLocale($headerTheme, $activeLocale);
            }
        }

        return view('admin::settings.web-theme.edit', compact(
            'theme',
            'opts',
            'activeLocale',
            'storeLocaleCodes',
            'innerHeroHeaderOpts'
        ));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $themeCode = (string) config('web.storefront_theme_code', 'web');

        $this->validate($request, [
            'name'       => 'required|string|max:191',
            'sort_order' => 'required|integer|min:0',
            'type'       => 'required|in:'.implode(',', self::typesForUpdate()),
        ]);

        /** @var WebThemeCustomization $theme */
        $theme = $this->webThemeCustomizationRepository->findOrFail($id);
        $isDefaultLayoutType = in_array($theme->type, [ThemeCustomization::WEB_HEADER, ThemeCustomization::WEB_FOOTER], true);

        if ($request->input('type') === ThemeCustomization::WEB_HEADER) {
            $dup = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::WEB_HEADER)
                ->where('id', '!=', $id)
                ->exists();
            if ($dup) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['type' => trans('admin::app.settings.web-theme.create.web-header-duplicate')]);
            }
        }

        if ($request->input('type') === ThemeCustomization::WEB_FOOTER) {
            $dup = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::WEB_FOOTER)
                ->where('id', '!=', $id)
                ->exists();
            if ($dup) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['type' => trans('admin::app.settings.web-theme.create.web-footer-duplicate')]);
            }
        }

        if ($request->input('type') === ThemeCustomization::IMMERSIVE_HERO) {
            $dup = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::IMMERSIVE_HERO)
                ->where('id', '!=', $id)
                ->exists();
            if ($dup) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['type' => trans('admin::app.settings.web-theme.create.immersive-hero-duplicate')]);
            }
        }

        if ($request->input('type') === ThemeCustomization::INNER_PAGE_HERO) {
            $dup = WebThemeCustomization::query()
                ->where('theme_code', $themeCode)
                ->where('type', ThemeCustomization::INNER_PAGE_HERO)
                ->where('id', '!=', $id)
                ->exists();
            if ($dup) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['type' => trans('admin::app.settings.web-theme.create.inner-page-hero-duplicate')]);
            }
        }

        $theme->update([
            'name'       => $request->input('name'),
            'sort_order' => (int) $request->input('sort_order'),
            'type'       => $isDefaultLayoutType ? $theme->type : $request->input('type'),
            'theme_code' => $themeCode,
            'status'     => $isDefaultLayoutType ? true : $request->boolean('status'),
        ]);

        $this->syncOptions($request, $theme);

        session()->flash('success', trans('admin::app.settings.web-theme.update-success'));

        return redirect()->route('admin.settings.web-theme.edit', [
            'id'     => $theme->id,
            'locale' => $request->input('locale'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $theme = $this->webThemeCustomizationRepository->findOrFail($id);

        if (in_array($theme->type, [ThemeCustomization::WEB_HEADER, ThemeCustomization::WEB_FOOTER], true)) {
            return new JsonResponse([
                'message' => 'Default web header/footer cannot be deleted.',
            ], 422);
        }

        Storage::disk('public')->deleteDirectory('web-theme/'.$theme->id);

        $this->webThemeCustomizationRepository->delete($id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.web-theme.delete-success'),
        ], 200);
    }

    protected function storeStaticEditorImage(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id'    => 'required|integer|exists:shop_theme_customizations,id',
            'image' => 'required|image|max:5120',
        ]);

        $theme = $this->webThemeCustomizationRepository->findOrFail((int) $request->input('id'));

        if ($theme->type !== 'static_content') {
            return response()->json(['message' => 'Invalid type'], 422);
        }

        $url = $this->webThemeCustomizationRepository->uploadStaticContentImage(
            $theme,
            $request->file('image')
        );

        return response()->json($url);
    }

    protected function syncOptions(Request $request, WebThemeCustomization $theme): void
    {
        $repo = $this->webThemeCustomizationRepository;
        $locale = $this->resolveRequestedStoreLocale($this->getStoreLocaleCodes());

        switch ($theme->type) {
            case 'static_content':
                $html = $repo->sanitizeHtml((string) $request->input('options.html', ''));
                $css = $repo->sanitizeCss((string) $request->input('options.css', ''));
                $this->persistLocalizedOptions($theme, $locale, ['html' => $html, 'css' => $css]);

                break;

            case 'immersive_hero':
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeImmersiveHeroOptions($request, $theme, $locale));

                break;

            case ThemeCustomization::SUPPLICATIONS_CONTENT:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeSupplicationsOptions($request));

                break;

            case ThemeCustomization::SECTION_DIVIDER:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeSectionDividerOptions($request));

                break;

            case ThemeCustomization::MAPS_SHOWCASE:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeMapsShowcaseOptions($request));

                break;

            case ThemeCustomization::PRAYER_TIMES:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizePrayerTimesOptions($request));

                break;

            case ThemeCustomization::WEB_HEADER:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeWebHeaderOptions($request, $theme, $locale));

                break;

            case ThemeCustomization::WEB_FOOTER:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeWebFooterOptions($request, $theme, $locale));

                break;

            case ThemeCustomization::INNER_PAGE_HERO:
                $this->persistLocalizedOptions($theme, $locale, $this->normalizeInnerPageHeroOptions($request, $theme, $locale));

                break;

            case 'image_carousel':
                $raw = $request->input('options', []);
                $merged = [];
                if (is_array($raw)) {
                    ksort($raw);
                    foreach ($raw as $i => $row) {
                        if (! is_array($row)) {
                            continue;
                        }
                        $file = $request->file('options.'.$i.'.image');
                        $path = (string) ($row['image_path'] ?? '');
                        $image = $file instanceof UploadedFile ? $file : $path;
                        if ($image === '') {
                            continue;
                        }
                        $merged[] = [
                            'image' => $image,
                            'link'  => (string) ($row['link'] ?? ''),
                            'title' => (string) ($row['title'] ?? ''),
                        ];
                    }
                }
                $deleted = $request->input('deleted_sliders');
                $deleted = is_array($deleted) ? $deleted : null;
                $localizedImageCarousel = $this->normalizeImageCarouselForLocale($theme, $locale, $merged, $deleted);
                $this->persistLocalizedOptions($theme, $locale, $localizedImageCarousel);

                break;

            default:
                $this->persistLocalizedOptions($theme, $locale, []);
        }
    }

    /**
     * @return list<string>
     */
    protected function getStoreLocaleCodes(): array
    {
        $codes = [];

        foreach (core()->storeLocales() as $locale) {
            $code = strtolower((string) ($locale['value'] ?? ''));
            if ($code !== '') {
                $codes[] = $code;
            }
        }

        if ($codes === []) {
            $codes[] = strtolower((string) config('app.locale', 'en'));
        }

        return array_values(array_unique($codes));
    }

    protected function resolveRequestedStoreLocale(array $storeLocaleCodes): string
    {
        $requested = strtolower((string) request()->input('locale', request()->query('locale', '')));
        if ($requested !== '' && in_array($requested, $storeLocaleCodes, true)) {
            return $requested;
        }

        return $storeLocaleCodes[0] ?? strtolower((string) config('app.locale', 'en'));
    }

    /**
     * @return array<string, mixed>
     */
    protected function localizedOptionsForLocale(WebThemeCustomization $theme, string $locale): array
    {
        $all = is_array($theme->options) ? $theme->options : [];
        $translations = $all['translations'] ?? null;

        if (! is_array($translations)) {
            return $all;
        }

        $defaultLocale = strtolower((string) ($all['default_locale'] ?? $this->defaultStoreLocale()));
        $resolved = $translations[$locale] ?? $translations[$defaultLocale] ?? [];

        return is_array($resolved) ? $resolved : [];
    }

    /**
     * @param  array<string, mixed>  $localizedPayload
     */
    protected function persistLocalizedOptions(WebThemeCustomization $theme, string $locale, array $localizedPayload): void
    {
        $existing = is_array($theme->options) ? $theme->options : [];
        $defaultLocale = strtolower((string) ($existing['default_locale'] ?? $this->defaultStoreLocale()));
        $translations = $existing['translations'] ?? [];

        if (! is_array($translations)) {
            $translations = [];
        }

        if ($translations === [] && $existing !== []) {
            $translations[$defaultLocale] = $existing;
        }

        $translations[$locale] = $localizedPayload;

        $theme->options = [
            'default_locale' => $defaultLocale,
            'translations'   => $translations,
        ];
                $theme->save();
        }

    protected function defaultStoreLocale(): string
    {
        $codes = $this->getStoreLocaleCodes();

        return $codes[0] ?? strtolower((string) config('app.locale', 'en'));
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, array{image: string}>|null  $deletedSliders
     * @return array<string, mixed>
     */
    protected function normalizeImageCarouselForLocale(
        WebThemeCustomization $theme,
        string $locale,
        array $rows,
        ?array $deletedSliders
    ): array {
        $existingLocale = $this->localizedOptionsForLocale($theme, $locale);
        $existingImages = is_array($existingLocale['images'] ?? null) ? $existingLocale['images'] : [];

        if (is_array($deletedSliders)) {
            foreach ($deletedSliders as $item) {
                $path = (string) ($item['image'] ?? '');
                if ($path === '') {
                    continue;
                }

                $rel = ltrim(str_replace('storage/', '', $path), '/');
                if ($rel !== '') {
                    Storage::disk('public')->delete($rel);
                }
            }
        }

        $images = [];

        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $link = (string) ($row['link'] ?? '');
            $title = (string) ($row['title'] ?? '');
            $imageVal = $row['image'] ?? '';

            if ($imageVal instanceof UploadedFile) {
                $stored = $imageVal->store("web-theme/{$theme->id}", 'public');
                $images[] = ['image' => $stored, 'link' => $link, 'title' => $title];
                continue;
            }

            if (is_string($imageVal) && $imageVal !== '') {
                $images[] = [
                    'image' => str_replace('storage/', '', ltrim($imageVal, '/')),
                    'link'  => $link,
                    'title' => $title,
                ];

                continue;
            }

            $fallback = $existingImages[$index] ?? null;
            if (is_array($fallback) && (string) ($fallback['image'] ?? '') !== '') {
                $images[] = [
                    'image' => (string) ($fallback['image'] ?? ''),
                    'link'  => $link,
                    'title' => $title,
                ];
            }
        }

        $existingLocale['images'] = $images;

        return $existingLocale;
    }

    /**
     * @param  mixed  $sections
     * @return list<array{links: list<array{title: string, url: string, sort_order: int}>}>
     */
    protected function normalizeFooterSections($sections): array
    {
        if (! is_array($sections)) {
            return [];
        }

        $out = [];

        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }

            $linksRaw = $section['links'] ?? [];
            if (! is_array($linksRaw)) {
                continue;
            }

            $links = [];
            $order = 0;

            foreach ($linksRaw as $link) {
                if (! is_array($link)) {
                    continue;
                }

                $title = trim((string) ($link['title'] ?? ''));
                $url = trim((string) ($link['url'] ?? ''));

                if ($title === '' && $url === '') {
                    continue;
                }

                $links[] = [
                    'title'      => $title,
                    'url'        => $url,
                    'sort_order' => (int) ($link['sort_order'] ?? $order),
                ];
                $order++;
            }

            usort($links, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

            if ($links !== []) {
                $out[] = ['links' => $links];
            }
        }

        return $out;
    }

    /**
     * @param  mixed  $raw
     * @return list<array{service_icon: string, title: string, description: string}>
     */
    protected function normalizeServices($raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $out = [];

        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $icon = trim((string) ($row['service_icon'] ?? 'icon-calendar'));

            if ($title === '' && $description === '') {
                continue;
            }

            if (! preg_match('/^[a-z0-9\-]+$/i', $icon)) {
                $icon = 'icon-calendar';
            }

            $out[] = [
                'service_icon' => $icon,
                'title'        => $title,
                'description'  => $description,
            ];
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizePortalFooterOptions(Request $request, WebThemeCustomization $theme, string $locale): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];

        $social = [];
        foreach (range(0, 7) as $i) {
            $icon = $this->sanitizeImmersiveFaClass((string) data_get($o, "social.$i.icon", ''));
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "social.$i.url", ''));
            if ($icon === '' && $url === '') {
                continue;
            }
            $social[] = ['icon' => $icon, 'url' => $url];
        }

        $colQuick = $this->normalizePortalFooterColumn(
            $o,
            'col_quick',
            $request->boolean('options.col_quick.show_chevron')
        );

        $brandLogoPath = $this->normalizePortalFooterBrandLogo($request, $theme, $o, $locale);

        $contactItems = [];
        foreach (range(0, 5) as $i) {
            $icon = $this->sanitizeImmersiveFaClass((string) data_get($o, "contact.items.$i.icon", ''));
            $text = mb_substr(trim((string) data_get($o, "contact.items.$i.text", '')), 0, 500);
            if ($icon === '' && $text === '') {
                continue;
            }
            $contactItems[] = ['icon' => $icon, 'text' => $text];
        }

        $bottomLinks = [];
        foreach (range(0, 9) as $i) {
            $label = mb_substr(trim((string) data_get($o, "bottom.links.$i.label", '')), 0, 191);
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "bottom.links.$i.url", ''));
            if ($label === '' && $url === '') {
                continue;
            }
            $bottomLinks[] = ['label' => $label, 'url' => $url];
        }

        $method = strtolower((string) data_get($o, 'newsletter.form_method', 'post'));
        if (! in_array($method, ['get', 'post'], true)) {
            $method = 'post';
        }

        return [
            'enabled' => $request->boolean('options.enabled'),
            'effects' => [
                'orbs'          => $request->boolean('options.effects.orbs'),
                'grid'          => $request->boolean('options.effects.grid'),
                'parallax'      => $request->boolean('options.effects.parallax'),
                'font_awesome'  => $request->boolean('options.effects.font_awesome'),
                'back_to_top'   => $request->boolean('options.effects.back_to_top'),
            ],
            'colors' => [
                'bg_start'   => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.bg_start', '#0a0a2a'), '#0a0a2a', true),
                'bg_end'     => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.bg_end', '#030318'), '#030318', true),
                'accent'     => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.accent', '#8b5cf6'), '#8b5cf6', true),
                'accent_2'   => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.accent_2', '#6366f1'), '#6366f1', true),
                'border_top' => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.border_top', 'rgba(139, 92, 246, 0.2)'), 'rgba(139, 92, 246, 0.2)', false),
                'text'       => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.text', '#ffffff'), '#ffffff', false),
                'text_muted' => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.text_muted', 'rgba(255,255,255,0.6)'), 'rgba(255,255,255,0.6)', false),
                'orb_1'      => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.orb_1', 'rgba(139, 92, 246, 0.6)'), 'rgba(139, 92, 246, 0.6)', false),
                'orb_2'      => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.orb_2', 'rgba(236, 72, 153, 0.5)'), 'rgba(236, 72, 153, 0.5)', false),
                'orb_3'      => $this->sanitizeImmersiveColor((string) data_get($o, 'colors.orb_3', 'rgba(59, 130, 246, 0.4)'), 'rgba(59, 130, 246, 0.4)', false),
            ],
            'brand' => [
                'logo_path'   => $brandLogoPath,
                'logo_icon'   => $this->sanitizeImmersiveFaClass((string) data_get($o, 'brand.logo_icon', 'fas fa-graduation-cap')),
                'title'       => mb_substr(trim((string) data_get($o, 'brand.title', '')), 0, 191),
                'description' => mb_substr(trim((string) data_get($o, 'brand.description', '')), 0, 2000),
            ],
            'social'    => $social,
            'col_quick' => $colQuick,
            'contact'      => [
                'title' => mb_substr(trim((string) data_get($o, 'contact.title', '')), 0, 191),
                'items' => $contactItems,
            ],
            'newsletter' => [
                'enabled'      => $request->boolean('options.newsletter.enabled'),
                'title'        => mb_substr(trim((string) data_get($o, 'newsletter.title', '')), 0, 191),
                'text'         => mb_substr(trim((string) data_get($o, 'newsletter.text', '')), 0, 1000),
                'placeholder'  => mb_substr(trim((string) data_get($o, 'newsletter.placeholder', '')), 0, 191),
                'button_label' => mb_substr(trim((string) data_get($o, 'newsletter.button_label', '')), 0, 191),
                'form_action'  => $this->sanitizeImmersiveUrl((string) data_get($o, 'newsletter.form_action', '')),
                'form_method'  => $method,
            ],
            'bottom' => [
                'copyright' => mb_substr(trim((string) data_get($o, 'bottom.copyright', '')), 0, 2000),
                'links'     => $bottomLinks,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $o
     * @return array{title: string, show_chevron: bool, links: list<array{label: string, url: string}>}
     */
    protected function normalizePortalFooterColumn(array $o, string $key, bool $showChevron): array
    {
        $links = [];
        foreach (range(0, 11) as $i) {
            $label = mb_substr(trim((string) data_get($o, "$key.links.$i.label", '')), 0, 191);
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "$key.links.$i.url", ''));
            if ($label === '' && $url === '') {
                continue;
            }
            $links[] = ['label' => $label, 'url' => $url];
        }

        return [
            'title'         => mb_substr(trim((string) data_get($o, "$key.title", '')), 0, 191),
            'show_chevron'  => $showChevron,
            'links'         => $links,
        ];
    }

    /**
     * Store or keep portal footer brand logo; paths are limited to this theme's folder on the public disk.
     */
    protected function normalizePortalFooterBrandLogo(Request $request, WebThemeCustomization $theme, array $o, string $locale): string
    {
        $prevLocalized = $this->localizedOptionsForLocale($theme, $locale);
        $prev = trim((string) data_get($prevLocalized, 'brand.logo_path', ''));

        if ($request->boolean('options.brand.remove_logo')) {
            if ($prev !== '' && Storage::disk('public')->exists($prev)) {
                Storage::disk('public')->delete($prev);
            }

            return '';
        }

        $file = $request->file('options.brand.logo_image');
        if ($file instanceof UploadedFile && $file->isValid()) {
            $mime = (string) $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (! in_array($mime, $allowed, true)) {
                return $this->assertPortalFooterLogoPathOwnedByTheme($prev, $theme);
            }

            if ($prev !== '' && Storage::disk('public')->exists($prev)) {
                Storage::disk('public')->delete($prev);
            }

            return $file->store('web-theme/'.$theme->id.'/footer', 'public');
        }

        $fromForm = trim((string) data_get($o, 'brand.logo_path', ''));

        return $this->assertPortalFooterLogoPathOwnedByTheme($fromForm !== '' ? $fromForm : $prev, $theme);
    }

    protected function assertPortalFooterLogoPathOwnedByTheme(string $path, WebThemeCustomization $theme): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        $prefix = 'web-theme/'.$theme->id.'/';
        if (! str_starts_with($path, $prefix) || str_contains($path, '..')) {
            return '';
        }

        if (! Storage::disk('public')->exists($path)) {
            return '';
        }

        return $path;
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeWebHeaderOptions(Request $request, WebThemeCustomization $theme, string $locale): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];

        $navPrimary = $this->normalizeWebHeaderNavPrimaryRows($o, $theme, $locale);

        $navSecondary = [];
        foreach (range(0, 2) as $i) {
            $label = mb_substr(trim((string) data_get($o, "nav_secondary.$i.label", '')), 0, 191);
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "nav_secondary.$i.url", ''));
            if ($label === '' && $url === '') {
                continue;
            }
            $navSecondary[] = [
                'label' => $label,
                'url'   => $url,
            ];
        }

        $nav = [];
        foreach ($navPrimary as $row) {
            $nav[] = [
                'label' => $row['label'],
                'url'   => $this->sanitizeImmersiveUrl(WebHeaderPrimaryTabs::resolveUrl($row['page_key'])),
                'icon'  => '',
            ];
        }
        foreach ($navSecondary as $row) {
            $nav[] = [
                'label' => $row['label'],
                'url'   => $row['url'],
                'icon'  => '',
            ];
        }

        $logoPath = $this->normalizeWebHeaderBrandLogo($request, $theme, $o, $locale);

        $colorsIn = is_array(data_get($o, 'colors')) ? $o['colors'] : [];
        $headerColorPrimary = $this->sanitizeWebThemeHexColor(data_get($colorsIn, 'primary'), '#1f6e2f');
        $headerColorSecondary = $this->sanitizeWebThemeHexColor(data_get($colorsIn, 'secondary'), '#2c8e3c');

        $prevLocalized = $this->localizedOptionsForLocale($theme, $locale);
        $prevLang = is_array(data_get($prevLocalized, 'lang')) ? $prevLocalized['lang'] : [];
        $prevLogin = is_array(data_get($prevLocalized, 'login')) ? $prevLocalized['login'] : [];

        return [
            'colors' => [
                'primary'   => $headerColorPrimary,
                'secondary' => $headerColorSecondary,
            ],
            'brand' => [
                'icon'       => $this->sanitizeImmersiveFaClass((string) data_get($o, 'brand.icon', 'fas fa-kaaba')),
                'title'      => mb_substr(trim((string) data_get($o, 'brand.title', '')), 0, 191),
                'subtitle'   => mb_substr(trim((string) data_get($o, 'brand.subtitle', '')), 0, 191),
                'logo_path'  => $logoPath,
            ],
            'nav_primary'   => $navPrimary,
            'nav_secondary' => $navSecondary,
            'nav'           => $nav,
            'lang' => [
                'show_switcher' => (bool) data_get($prevLang, 'show_switcher', true),
                'button_label'  => '',
            ],
            'login' => [
                'show'  => (bool) data_get($prevLogin, 'show', true),
                'label' => '',
                'url'   => $this->sanitizeImmersiveUrl((string) data_get($prevLogin, 'url', '')),
            ],
        ];
    }

    /**
     * @return list<array{page_key: string, label: string}>
     */
    protected function normalizeWebHeaderNavPrimaryRows(array $o, WebThemeCustomization $theme, string $locale): array
    {
        $allowedOrder = WebHeaderPrimaryTabs::defaultKeyOrder();
        if ($allowedOrder === []) {
            return [];
        }

        $rows = [];
        foreach (range(0, max(0, count($allowedOrder) - 1)) as $i) {
            $rows[] = [
                'page_key' => (string) data_get($o, "nav_primary.$i.page_key", ''),
                'label'    => mb_substr(trim((string) data_get($o, "nav_primary.$i.label", '')), 0, 191),
            ];
        }

        if ($this->webHeaderNavPrimaryRowsAreValidPermutation($rows, $allowedOrder)) {
            return $rows;
        }

        return $this->webHeaderNavPrimaryFallbackFromStored($theme, $locale, $allowedOrder);
    }

    /**
     * @param  list<array{page_key: string, label: string}>  $rows
     * @param  list<string>  $allowedOrder
     */
    protected function webHeaderNavPrimaryRowsAreValidPermutation(array $rows, array $allowedOrder): bool
    {
        if (count($rows) !== count($allowedOrder)) {
            return false;
        }

        $seen = [];
        foreach ($rows as $r) {
            $k = $r['page_key'] ?? '';
            if ($k === '' || ! in_array($k, $allowedOrder, true) || isset($seen[$k])) {
                return false;
            }
            $seen[$k] = true;
        }

        return count($seen) === count($allowedOrder);
    }

    /**
     * @param  list<string>  $allowedOrder
     * @return list<array{page_key: string, label: string}>
     */
    protected function webHeaderNavPrimaryFallbackFromStored(WebThemeCustomization $theme, string $locale, array $allowedOrder): array
    {
        $prev = $this->localizedOptionsForLocale($theme, $locale);
        $legacyNav = is_array($prev['nav'] ?? null) ? $prev['nav'] : [];
        $editorRows = WebHeaderPrimaryTabs::editorRowsFromOptions($prev, $legacyNav);

        $out = [];
        foreach ($editorRows as $r) {
            $out[] = [
                'page_key' => (string) ($r['pageKey'] ?? ''),
                'label'    => mb_substr(trim((string) ($r['label'] ?? '')), 0, 191),
            ];
        }

        if ($this->webHeaderNavPrimaryRowsAreValidPermutation($out, $allowedOrder)) {
            return $out;
        }

        $default = [];
        foreach ($allowedOrder as $k) {
            $default[] = ['page_key' => $k, 'label' => ''];
        }

        return $default;
    }

    /**
     * Store or remove header brand logo; paths are limited to this theme's folder on the public disk.
     */
    protected function normalizeWebHeaderBrandLogo(Request $request, WebThemeCustomization $theme, array $o, string $locale): string
    {
        $prevLocalized = $this->localizedOptionsForLocale($theme, $locale);
        $prev = trim((string) data_get($prevLocalized, 'brand.logo_path', ''));

        if ($request->boolean('options.brand.remove_logo')) {
            if ($prev !== '' && Storage::disk('public')->exists($prev)) {
                Storage::disk('public')->delete($prev);
            }

            return '';
        }

        $file = $request->file('options.brand.logo_image');
        if ($file instanceof UploadedFile && $file->isValid()) {
            $mime = (string) $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (! in_array($mime, $allowed, true)) {
                return $this->assertPortalFooterLogoPathOwnedByTheme($prev, $theme);
            }

            if ($prev !== '' && Storage::disk('public')->exists($prev)) {
                Storage::disk('public')->delete($prev);
            }

            return $file->store('web-theme/'.$theme->id.'/header', 'public');
        }

        $fromForm = trim((string) data_get($o, 'brand.logo_path', ''));

        return $this->assertPortalFooterLogoPathOwnedByTheme($fromForm !== '' ? $fromForm : $prev, $theme);
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeWebFooterOptions(Request $request, WebThemeCustomization $theme, string $locale): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];

        $prevLocalized = $this->localizedOptionsForLocale($theme, $locale);
        $logoPath = $this->normalizePortalFooterBrandLogo($request, $theme, $o, $locale);

        $footerColorsIn = is_array(data_get($o, 'colors')) ? $o['colors'] : [];
        $footerColorPrimary = $this->sanitizeWebThemeHexColor(data_get($footerColorsIn, 'primary'), '#d4af37');
        $footerColorSecondary = $this->sanitizeWebThemeHexColor(data_get($footerColorsIn, 'secondary'), '#0d2a1a');

        $social = [];
        foreach (range(0, 7) as $i) {
            $icon = $this->sanitizeImmersiveFaClass((string) data_get($o, "social.$i.icon", ''));
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "social.$i.url", ''));
            $aria = mb_substr(trim((string) data_get($o, "social.$i.aria_label", '')), 0, 191);
            if ($icon === '' && $url === '') {
                continue;
            }
            $social[] = ['icon' => $icon, 'url' => $url, 'aria_label' => $aria];
        }

        $colExplore = $this->normalizePortalFooterColumn(
            $o,
            'col_explore',
            $request->boolean('options.col_explore.show_chevron')
        );

        $colSupport = $this->normalizePortalFooterColumn(
            $o,
            'col_support',
            $request->boolean('options.col_support.show_chevron')
        );

        $contactItems = [];
        foreach (range(0, 5) as $i) {
            $icon = $this->sanitizeImmersiveFaClass((string) data_get($o, "contact.items.$i.icon", ''));
            $text = mb_substr(trim((string) data_get($o, "contact.items.$i.text", '')), 0, 500);
            if ($icon === '' && $text === '') {
                continue;
            }
            $contactItems[] = ['icon' => $icon, 'text' => $text];
        }

        $bottomLinks = [];
        foreach (range(0, 9) as $i) {
            $label = mb_substr(trim((string) data_get($o, "bottom.links.$i.label", '')), 0, 191);
            $url = $this->sanitizeImmersiveUrl((string) data_get($o, "bottom.links.$i.url", ''));
            if ($label === '' && $url === '') {
                continue;
            }
            $bottomLinks[] = ['label' => $label, 'url' => $url];
        }

        return [
            'enabled' => (bool) data_get($prevLocalized, 'enabled', true),
            'visibility' => [
                'brand'       => $request->boolean('options.visibility.brand'),
                'social'      => $request->boolean('options.visibility.social'),
                'explore'     => $request->boolean('options.visibility.explore'),
                'support'     => $request->boolean('options.visibility.support'),
                'contact'     => $request->boolean('options.visibility.contact'),
                'subscribe'   => $request->boolean('options.visibility.subscribe'),
                'bottom'      => $request->boolean('options.visibility.bottom'),
                'bottom_mini' => $request->boolean('options.visibility.bottom_mini'),
            ],
            'effects' => [
                'back_to_top' => $request->boolean('options.effects.back_to_top'),
            ],
            'colors' => [
                'primary'   => $footerColorPrimary,
                'secondary' => $footerColorSecondary,
            ],
            'brand' => [
                'logo_path'   => $logoPath,
                'icon'        => $this->sanitizeImmersiveFaClass((string) data_get($o, 'brand.icon', 'fas fa-kaaba')),
                'title'       => mb_substr(trim((string) data_get($o, 'brand.title', '')), 0, 191),
                'description' => mb_substr(trim((string) data_get($o, 'brand.description', '')), 0, 2000),
                'trust'       => mb_substr(trim((string) data_get($o, 'brand.trust', '')), 0, 500),
            ],
            'social'       => $social,
            'col_explore'  => $colExplore,
            'col_support'  => $colSupport,
            'contact'      => [
                'title' => mb_substr(trim((string) data_get($o, 'contact.title', '')), 0, 191),
                'items' => $contactItems,
            ],
            'subscribe' => [
                'title'        => mb_substr(trim((string) data_get($o, 'subscribe.title', '')), 0, 191),
                'placeholder'  => mb_substr(trim((string) data_get($o, 'subscribe.placeholder', '')), 0, 191),
                'privacy'      => mb_substr(trim((string) data_get($o, 'subscribe.privacy', '')), 0, 500),
                'success_msg'  => mb_substr(trim((string) data_get($o, 'subscribe.success_msg', '')), 0, 500),
                'invalid_msg'  => mb_substr(trim((string) data_get($o, 'subscribe.invalid_msg', '')), 0, 500),
            ],
            'bottom' => [
                'copyright'      => mb_substr(trim((string) data_get($o, 'bottom.copyright', '')), 0, 2000),
                'mini_nav_label' => mb_substr(trim((string) data_get($o, 'bottom.mini_nav_label', '')), 0, 191),
                'links'          => $bottomLinks,
            ],
        ];
    }

    /**
     * Normalize a hex color for web header/footer theme options (#RGB or #RRGGBB).
     */
    protected function sanitizeWebThemeHexColor(mixed $value, string $default): string
    {
        $s = trim((string) $value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $s, $m)) {
            $h = $m[1];
            if (strlen($h) === 3) {
                $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            }

            return '#'.strtoupper($h);
        }

        $d = trim($default);
        if (preg_match('/^#([0-9a-fA-F]{6})$/', $d, $dm)) {
            return '#'.strtoupper($dm[1]);
        }

        return '#1F6E2F';
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeInnerPageHeroOptions(Request $request, WebThemeCustomization $theme, string $locale): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];

        $out = [
            'visible'       => filter_var($o['visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'gradient_from' => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_from'] ?? ''), '#0D2A1A'),
            'gradient_mid'  => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_mid'] ?? ''), '#1A3A2A'),
            'gradient_to'   => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_to'] ?? ''), '#0D2A1A'),
            'gold'          => $this->sanitizeInnerPageHeroHex((string) ($o['gold'] ?? ''), '#D4AF37'),
            'wave_fill'     => $this->sanitizeInnerPageHeroHex((string) ($o['wave_fill'] ?? ''), '#FEFAF5'),
            'pages'         => [],
        ];

        $rawPages = $o['pages'] ?? [];
        $rawPages = is_array($rawPages) ? $rawPages : [];

        foreach (WebHeaderPrimaryTabs::innerHeroPageKeys() as $pageKey) {
            $slice = $rawPages[$pageKey] ?? [];
            $slice = is_array($slice) ? $slice : [];
            $out['pages'][$pageKey] = $this->normalizeInnerPageHeroPageSlice($slice);
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    protected function normalizeInnerPageHeroPageSlice(array $p): array
    {
        return [
            'badge_show'      => filter_var($p['badge_show'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'badge_icon'      => $this->sanitizeImmersiveFaClass((string) ($p['badge_icon'] ?? '')),
            'badge_text'      => mb_substr(trim((string) ($p['badge_text'] ?? '')), 0, 191),
            'title'           => mb_substr(trim((string) ($p['title'] ?? '')), 0, 191),
            'description'     => mb_substr(trim((string) ($p['description'] ?? '')), 0, 2000),
            'primary_show'    => filter_var($p['primary_show'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'primary_label'   => mb_substr(trim((string) ($p['primary_label'] ?? '')), 0, 191),
            'primary_url'     => $this->sanitizeImmersiveUrl((string) ($p['primary_url'] ?? '')),
            'primary_icon'    => $this->sanitizeImmersiveFaClass((string) ($p['primary_icon'] ?? '')),
            'secondary_show'  => filter_var($p['secondary_show'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'secondary_label' => mb_substr(trim((string) ($p['secondary_label'] ?? '')), 0, 191),
            'secondary_url'   => $this->sanitizeImmersiveUrl((string) ($p['secondary_url'] ?? '')),
            'secondary_icon'  => $this->sanitizeImmersiveFaClass((string) ($p['secondary_icon'] ?? '')),
        ];
    }

    protected function sanitizeInnerPageHeroHex(string $value, string $fallback): string
    {
        $value = strtoupper(trim($value));
        if (preg_match('/^#[0-9A-F]{6}$/', $value)) {
            return $value;
        }

        $fb = strtoupper(trim($fallback));

        return preg_match('/^#[0-9A-F]{6}$/', $fb) ? $fb : '#0D2A1A';
    }

    /** Optional hex for section divider parchment overrides; empty = use theme defaults. */
    protected function sanitizeSectionDividerOptionalHex(string $value): string
    {
        $value = strtoupper(trim($value));
        if ($value === '') {
            return '';
        }

        return preg_match('/^#[0-9A-F]{6}$/', $value) ? $value : '';
    }

    /** @var list<string> */
    private const SECTION_DIVIDER_VARIANTS = ['inset_card', 'full_bleed', 'content_heading', 'parchment_card'];

    protected function normalizeSectionDividerVariant(string $value): string
    {
        $value = strtolower(trim($value));

        return in_array($value, self::SECTION_DIVIDER_VARIANTS, true) ? $value : 'inset_card';
    }

    /**
     * Homepage section divider: multiple visual presets (gradient band, heading strip, parchment card).
     *
     * @return array<string, mixed>
     */
    protected function normalizeSectionDividerOptions(Request $request): array
    {
        $o = $request->input('options', []);
        $o = is_array($o) ? $o : [];

        return [
            'variant'         => $this->normalizeSectionDividerVariant((string) ($o['variant'] ?? '')),
            'visible'         => filter_var($o['visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'gradient_from'   => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_from'] ?? ''), '#0D2A1A'),
            'gradient_mid'    => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_mid'] ?? ''), '#1A3A2A'),
            'gradient_to'     => $this->sanitizeInnerPageHeroHex((string) ($o['gradient_to'] ?? ''), '#0D2A1A'),
            'gold'            => $this->sanitizeInnerPageHeroHex((string) ($o['gold'] ?? ''), '#D4AF37'),
            'wave_fill'       => $this->sanitizeInnerPageHeroHex((string) ($o['wave_fill'] ?? ''), '#FEFAF5'),
            'badge_show'      => filter_var($o['badge_show'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'badge_icon'      => $this->sanitizeImmersiveFaClass((string) ($o['badge_icon'] ?? '')),
            'badge_text'      => mb_substr(trim((string) ($o['badge_text'] ?? '')), 0, 191),
            'title'           => mb_substr(trim((string) ($o['title'] ?? '')), 0, 191),
            'description'     => mb_substr(trim((string) ($o['description'] ?? '')), 0, 2000),
            'primary_show'    => filter_var($o['primary_show'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'primary_label'   => mb_substr(trim((string) ($o['primary_label'] ?? '')), 0, 191),
            'primary_url'     => $this->sanitizeImmersiveUrl((string) ($o['primary_url'] ?? '')),
            'primary_icon'    => $this->sanitizeImmersiveFaClass((string) ($o['primary_icon'] ?? '')),
            'secondary_show'  => filter_var($o['secondary_show'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'secondary_label' => mb_substr(trim((string) ($o['secondary_label'] ?? '')), 0, 191),
            'secondary_url'   => $this->sanitizeImmersiveUrl((string) ($o['secondary_url'] ?? '')),
            'secondary_icon'  => $this->sanitizeImmersiveFaClass((string) ($o['secondary_icon'] ?? '')),
            'parchment_start' => $this->sanitizeSectionDividerOptionalHex((string) ($o['parchment_start'] ?? '')),
            'parchment_mid'   => $this->sanitizeSectionDividerOptionalHex((string) ($o['parchment_mid'] ?? '')),
            'parchment_end'   => $this->sanitizeSectionDividerOptionalHex((string) ($o['parchment_end'] ?? '')),
        ];
    }
}
