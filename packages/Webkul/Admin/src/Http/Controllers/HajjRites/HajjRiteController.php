<?php

namespace Webkul\Admin\Http\Controllers\HajjRites;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\HajjRites\HajjRiteDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Manasik\Models\Dua;
use Webkul\Manasik\Models\HajjRite;

class HajjRiteController extends Controller
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
        if (! bouncer()->hasPermission('hajj_rites')) {
            abort(403);
        }
    }

    protected function assertCanCreate(): void
    {
        if (! bouncer()->hasPermission('hajj_rites.create')) {
            abort(403);
        }
    }

    protected function assertCanEdit(): void
    {
        if (! bouncer()->hasPermission('hajj_rites.edit')) {
            abort(403);
        }
    }

    protected function assertCanDelete(): void
    {
        if (! bouncer()->hasPermission('hajj_rites.delete')) {
            abort(403);
        }
    }

    /**
     * @return array{default_locale: string, translations: array<string, array{tab_label: string, title: string, subtitle: string, badge: string, description: string, info_items: list<array{text: string}>}>}
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

            $infoItemsRaw = $slice['info_items'] ?? [];
            if (! is_array($infoItemsRaw)) {
                $infoItemsRaw = [];
            }

            $infoItems = [];
            foreach ($infoItemsRaw as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $text = mb_substr(trim((string) ($row['text'] ?? '')), 0, 2000);
                if ($text === '') {
                    continue;
                }
                $infoItems[] = ['text' => $text];
            }

            $translations[$code] = [
                'tab_label' => mb_substr(trim((string) ($slice['tab_label'] ?? '')), 0, 500),
                'title' => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
                'subtitle' => mb_substr(trim((string) ($slice['subtitle'] ?? '')), 0, 500),
                'badge' => mb_substr(trim((string) ($slice['badge'] ?? '')), 0, 200),
                'description' => mb_substr(trim((string) ($slice['description'] ?? '')), 0, 50000),
                'info_items' => $infoItems,
            ];
        }

        return [
            'default_locale' => $defaultLocale,
            'translations' => $translations,
        ];
    }

    /**
     * @return list<int>
     */
    protected function parseLinkedDuaIds(Request $request): array
    {
        $raw = $request->input('linked_dua_ids', '');
        if (is_array($raw)) {
            $ids = array_map('intval', $raw);
        } else {
            $parts = preg_split('/[\s,،]+/u', (string) $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $ids = [];
            foreach ($parts as $p) {
                $ids[] = (int) $p;
            }
        }

        $ids = array_values(array_unique(array_filter($ids, static fn (int $id): bool => $id > 0)));

        return $ids;
    }

    /**
     * @param  list<int>  $ids
     */
    protected function validateDuaIdsExist(array $ids): void
    {
        if ($ids === []) {
            return;
        }

        $count = Dua::query()->whereIn('id', $ids)->count();
        if ($count !== count($ids)) {
            throw ValidationException::withMessages([
                'linked_dua_ids' => [trans('admin::app.settings.hajj-rites.form.linked-duas-invalid')],
            ]);
        }
    }

    protected function syncLinkedDuas(HajjRite $rite, array $orderedDuaIds): void
    {
        $sync = [];
        foreach (array_values($orderedDuaIds) as $pos => $duaId) {
            $sync[(int) $duaId] = ['sort_order' => (int) $pos];
        }
        $rite->duas()->sync($sync);
    }

    /**
     * @return Collection<int, \Webkul\Manasik\Models\Dua>
     */
    protected function duasForSelect()
    {
        return Dua::query()
            ->with('section')
            ->orderBy('manasik_dua_section_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function index(): View|JsonResponse
    {
        $this->assertCanView();

        if (request()->ajax()) {
            return datagrid(HajjRiteDataGrid::class)->process();
        }

        return view('admin::hajj-rites.index');
    }

    public function create(): View
    {
        $this->assertCanView();
        $this->assertCanCreate();

        $storeLocaleCodes = $this->getStoreLocaleCodes();
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);
        $duas = $this->duasForSelect();

        return view('admin::hajj-rites.create', compact('storeLocaleCodes', 'activeLocale', 'duas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanCreate();

        Validator::make($request->all(), [
            'slug' => ['required', 'string', 'max:128', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('manasik_hajj_rites', 'slug')],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
            'linked_dua_ids' => ['nullable'],
        ])->validate();

        $duaIds = $this->parseLinkedDuaIds($request);
        $this->validateDuaIdsExist($duaIds);

        $content = $this->normalizeContentFromRequest($request);

        $rite = HajjRite::query()->create([
            'slug' => Str::lower(trim((string) $request->input('slug'))),
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
        ]);

        $this->syncLinkedDuas($rite, $duaIds);

        $to = route('admin.manasik-hajj-rites.edit', $rite->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.hajj-rites.create-success'));
    }

    public function edit(int $id): View
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $rite = HajjRite::query()->with(['duas' => static fn ($q) => $q->orderByPivot('sort_order')])->findOrFail($id);
        $storeLocaleCodes = $this->getStoreLocaleCodes();

        $content = is_array($rite->content) ? $rite->content : [];
        $translations = is_array($content['translations'] ?? null) ? $content['translations'] : [];
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);
        $duas = $this->duasForSelect();

        $linkedIds = $rite->duas->pluck('id')->all();
        $linkedDuaIdsString = implode(', ', $linkedIds);

        return view('admin::hajj-rites.edit', compact(
            'rite',
            'storeLocaleCodes',
            'translations',
            'activeLocale',
            'duas',
            'linkedDuaIdsString'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $rite = HajjRite::query()->findOrFail($id);

        Validator::make($request->all(), [
            'slug' => ['required', 'string', 'max:128', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('manasik_hajj_rites', 'slug')->ignore($rite->id)],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
            'linked_dua_ids' => ['nullable'],
        ])->validate();

        $duaIds = $this->parseLinkedDuaIds($request);
        $this->validateDuaIdsExist($duaIds);

        $content = $this->normalizeContentFromRequest($request);

        $rite->update([
            'slug' => Str::lower(trim((string) $request->input('slug'))),
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
        ]);

        $this->syncLinkedDuas($rite, $duaIds);

        $to = route('admin.manasik-hajj-rites.edit', $rite->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.hajj-rites.update-success'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanDelete();

        HajjRite::query()->findOrFail($id)->delete();

        return redirect()
            ->route('admin.manasik-hajj-rites.index')
            ->with('success', trans('admin::app.settings.hajj-rites.delete-success'));
    }
}
