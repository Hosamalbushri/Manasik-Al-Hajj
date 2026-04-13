<?php

namespace Webkul\Admin\Http\Controllers\AdhkarDuas;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\AdhkarDuas\WebDuaSectionDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Manasik\Models\DuaSection;

class WebDuaSectionController extends Controller
{
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

    protected function assertCanView(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.dua_sections')) {
            abort(403);
        }
    }

    protected function assertCanCreate(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.dua_sections.create')) {
            abort(403);
        }
    }

    protected function assertCanEdit(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.dua_sections.edit')) {
            abort(403);
        }
    }

    protected function assertCanDelete(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.dua_sections.delete')) {
            abort(403);
        }
    }

    /**
     * @return array{default_locale: string, translations: array<string, array{title: string}>}
     */
    protected function normalizeContentFromRequest(Request $request): array
    {
        $codes = $this->getStoreLocaleCodes();
        $defaultLocale = $codes[0] ?? strtolower((string) config('app.locale', 'en'));

        $raw = $request->input('content.translations', []);
        if (! is_array($raw)) {
            $raw = [];
        }

        $translations = [];

        foreach ($codes as $code) {
            $slice = $raw[$code] ?? [];
            if (! is_array($slice)) {
                $slice = [];
            }

            $translations[$code] = [
                'title' => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
            ];
        }

        return [
            'default_locale' => $defaultLocale,
            'translations' => $translations,
        ];
    }

    protected function makeUniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        if ($slug === '') {
            $slug = 'section';
        }
        $slug = substr($slug, 0, 60);
        $original = $slug;
        $n = 0;
        while (DuaSection::query()->where('slug', $slug)->exists()) {
            $n++;
            $suffix = '-'.$n;
            $slug = substr($original, 0, 64 - strlen($suffix)).$suffix;
        }

        return $slug;
    }

    public function index(): View|JsonResponse
    {
        $this->assertCanView();

        if (request()->ajax()) {
            return datagrid(WebDuaSectionDataGrid::class)->process();
        }

        return view('admin::adhkar-duas.dua-sections.index');
    }

    public function create(): View
    {
        $this->assertCanView();
        $this->assertCanCreate();

        $storeLocaleCodes = $this->getStoreLocaleCodes();
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);

        return view('admin::adhkar-duas.dua-sections.create', compact('storeLocaleCodes', 'activeLocale'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanCreate();

        Validator::make($request->all(), [
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
        ])->validate();

        $content = $this->normalizeContentFromRequest($request);
        $codes = $this->getStoreLocaleCodes();
        $defaultLocale = $content['default_locale'];
        $title = (string) ($content['translations'][$defaultLocale]['title'] ?? '');
        foreach ($codes as $c) {
            if ($title === '' && ($content['translations'][$c]['title'] ?? '') !== '') {
                $title = (string) $content['translations'][$c]['title'];
                break;
            }
        }

        $slug = $this->makeUniqueSlug($title !== '' ? $title : 'section-'.Str::random(6));

        $row = DuaSection::query()->create([
            'slug' => $slug,
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
        ]);

        $to = route('admin.adhkar-duas.dua-sections.edit', $row->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.dua-sections.create-success'));
    }

    public function edit(int $id): View
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $section = DuaSection::query()->findOrFail($id);
        $storeLocaleCodes = $this->getStoreLocaleCodes();

        $content = is_array($section->content) ? $section->content : [];
        $translations = is_array($content['translations'] ?? null) ? $content['translations'] : [];
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);

        return view('admin::adhkar-duas.dua-sections.edit', compact(
            'section',
            'storeLocaleCodes',
            'translations',
            'activeLocale'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $section = DuaSection::query()->findOrFail($id);

        Validator::make($request->all(), [
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
        ])->validate();

        $content = $this->normalizeContentFromRequest($request);

        $section->update([
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
        ]);

        $to = route('admin.adhkar-duas.dua-sections.edit', $section->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.dua-sections.update-success'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanDelete();

        DuaSection::query()->findOrFail($id)->delete();

        return redirect()
            ->route('admin.adhkar-duas.dua-sections.index')
            ->with('success', trans('admin::app.settings.dua-sections.delete-success'));
    }
}
