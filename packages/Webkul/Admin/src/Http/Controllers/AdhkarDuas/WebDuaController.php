<?php

namespace Webkul\Admin\Http\Controllers\AdhkarDuas;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\AdhkarDuas\WebDuaDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Web\Models\WebDua;
use Webkul\Web\Models\WebDuaSection;

class WebDuaController extends Controller
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
        if (! bouncer()->hasPermission('adhkar_duas.duas')) {
            abort(403);
        }
    }

    protected function assertCanCreate(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.duas.create')) {
            abort(403);
        }
    }

    protected function assertCanEdit(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.duas.edit')) {
            abort(403);
        }
    }

    protected function assertCanDelete(): void
    {
        if (! bouncer()->hasPermission('adhkar_duas.duas.delete')) {
            abort(403);
        }
    }

    /**
     * @return array{default_locale: string, translations: array<string, array{title: string, text: string, reference: string}>}
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
                'title'     => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
                'text'      => mb_substr(trim((string) ($slice['text'] ?? '')), 0, 10000),
                'reference' => mb_substr(trim((string) ($slice['reference'] ?? '')), 0, 1000),
            ];
        }

        return [
            'default_locale' => $defaultLocale,
            'translations'   => $translations,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Webkul\Web\Models\WebDuaSection>
     */
    protected function sectionsForSelect()
    {
        return WebDuaSection::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function index(): View|\Illuminate\Http\JsonResponse
    {
        $this->assertCanView();

        if (request()->ajax()) {
            return datagrid(WebDuaDataGrid::class)->process();
        }

        return view('admin::adhkar-duas.duas.index');
    }

    public function create(): View
    {
        $this->assertCanView();
        $this->assertCanCreate();

        $storeLocaleCodes = $this->getStoreLocaleCodes();
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);
        $sections = $this->sectionsForSelect();

        $prefillSectionId = (int) request()->query('web_dua_section_id', 0);
        if ($prefillSectionId > 0 && ! WebDuaSection::query()->whereKey($prefillSectionId)->exists()) {
            $prefillSectionId = 0;
        }

        return view('admin::adhkar-duas.duas.create', compact('storeLocaleCodes', 'activeLocale', 'sections', 'prefillSectionId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanCreate();

        Validator::make($request->all(), [
            'web_dua_section_id' => ['required', 'integer', 'exists:web_dua_sections,id'],
            'sort_order'         => ['required', 'integer', 'min:0', 'max:999999'],
            'status'             => ['nullable', 'in:0,1'],
        ])->validate();

        $content = $this->normalizeContentFromRequest($request);

        $row = WebDua::query()->create([
            'web_dua_section_id' => (int) $request->input('web_dua_section_id'),
            'sort_order'         => (int) $request->input('sort_order'),
            'status'             => $request->boolean('status', true),
            'content'            => $content,
        ]);

        $to = route('admin.adhkar-duas.duas.edit', $row->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.duas.create-success'));
    }

    public function edit(int $id): View
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $dua = WebDua::query()->findOrFail($id);
        $storeLocaleCodes = $this->getStoreLocaleCodes();

        $content = is_array($dua->content) ? $dua->content : [];
        $translations = is_array($content['translations'] ?? null) ? $content['translations'] : [];
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);
        $sections = $this->sectionsForSelect();

        return view('admin::adhkar-duas.duas.edit', compact(
            'dua',
            'storeLocaleCodes',
            'translations',
            'activeLocale',
            'sections'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $dua = WebDua::query()->findOrFail($id);

        Validator::make($request->all(), [
            'web_dua_section_id' => ['required', 'integer', 'exists:web_dua_sections,id'],
            'sort_order'         => ['required', 'integer', 'min:0', 'max:999999'],
            'status'             => ['nullable', 'in:0,1'],
        ])->validate();

        $content = $this->normalizeContentFromRequest($request);

        $dua->update([
            'web_dua_section_id' => (int) $request->input('web_dua_section_id'),
            'sort_order'         => (int) $request->input('sort_order'),
            'status'             => $request->boolean('status', true),
            'content'            => $content,
        ]);

        $to = route('admin.adhkar-duas.duas.edit', $dua->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.duas.update-success'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanDelete();

        WebDua::query()->findOrFail($id)->delete();

        return redirect()
            ->route('admin.adhkar-duas.duas.index')
            ->with('success', trans('admin::app.settings.duas.delete-success'));
    }
}
