<?php

namespace Webkul\Admin\Http\Controllers\MapLocations;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\MapLocations\WebMapLocationDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Manasik\Models\MapLocation;

class WebMapLocationController extends Controller
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
        if (! bouncer()->hasPermission('map_locations')) {
            abort(403);
        }
    }

    protected function assertCanCreate(): void
    {
        if (! bouncer()->hasPermission('map_locations.create')) {
            abort(403);
        }
    }

    protected function assertCanEdit(): void
    {
        if (! bouncer()->hasPermission('map_locations.edit')) {
            abort(403);
        }
    }

    protected function assertCanDelete(): void
    {
        if (! bouncer()->hasPermission('map_locations.delete')) {
            abort(403);
        }
    }

    /**
     * @return array{default_locale: string, translations: array<string, array<string, mixed>>}
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

            $featuresRaw = (string) ($slice['features_raw'] ?? '');
            $lines = preg_split('/\R/u', $featuresRaw) ?: [];
            $features = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $features[] = mb_substr($line, 0, 500);
                }
                if (count($features) >= 30) {
                    break;
                }
            }

            $translations[$code] = [
                'title' => mb_substr(trim((string) ($slice['title'] ?? '')), 0, 500),
                'badge' => mb_substr(trim((string) ($slice['badge'] ?? '')), 0, 191),
                'description' => mb_substr(trim((string) ($slice['description'] ?? '')), 0, 5000),
                'detail_alert' => '',
                'features' => $features,
            ];
        }

        return [
            'default_locale' => $defaultLocale,
            'translations' => $translations,
        ];
    }

    protected function deleteStoredImageIfLocal(?string $path): void
    {
        $path = trim((string) $path);
        if ($path === '' || preg_match('#^https?://#i', $path)) {
            return;
        }

        $rel = ltrim(str_replace('storage/', '', $path), '/');
        if ($rel !== '') {
            Storage::disk('public')->delete($rel);
        }
    }

    protected function firstUploadedMapImage(Request $request): ?UploadedFile
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');

        if ($file instanceof UploadedFile) {
            return $file->isValid() ? $file : null;
        }

        if (is_array($file)) {
            foreach ($file as $uploaded) {
                if ($uploaded instanceof UploadedFile && $uploaded->isValid()) {
                    return $uploaded;
                }
            }
        }

        return null;
    }

    public function index(): View|JsonResponse
    {
        $this->assertCanView();

        if (request()->ajax()) {
            return datagrid(WebMapLocationDataGrid::class)->process();
        }

        return view('admin::map-locations.index');
    }

    public function create(): View
    {
        $this->assertCanView();
        $this->assertCanCreate();

        $storeLocaleCodes = $this->getStoreLocaleCodes();
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);

        return view('admin::map-locations.create', compact('storeLocaleCodes', 'activeLocale'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanCreate();

        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'zoom' => ['nullable', 'integer', 'min:1', 'max:21'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
            'image.*' => ['nullable', 'image', 'max:5120'],
        ]);
        $validator->validate();

        $content = $this->normalizeContentFromRequest($request);

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $pendingSlug = 'pending-'.str_replace('-', '', Str::uuid()->toString());

        $row = MapLocation::query()->create([
            'slug' => substr($pendingSlug, 0, 64),
            'map_id' => 'pending',
            'latitude' => (float) $lat,
            'longitude' => (float) $lng,
            'zoom' => $request->filled('zoom') ? (int) $request->input('zoom') : 15,
            'embed' => null,
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
            'image' => null,
        ]);

        $row->update([
            'slug' => 'map-loc-'.$row->id,
            'map_id' => 'map-'.$row->id,
        ]);

        if ($uploaded = $this->firstUploadedMapImage($request)) {
            $path = $uploaded->store('web-map-locations/'.$row->id, 'public');
            $row->update(['image' => $path]);
        }

        $to = route('admin.map-locations.edit', $row->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.map-locations.create-success'));
    }

    public function edit(int $id): View
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $location = MapLocation::query()->findOrFail($id);
        $storeLocaleCodes = $this->getStoreLocaleCodes();

        $content = is_array($location->content) ? $location->content : [];
        $translations = is_array($content['translations'] ?? null) ? $content['translations'] : [];
        $activeLocale = $this->resolveRequestedStoreLocale($storeLocaleCodes);

        return view('admin::map-locations.edit', compact(
            'location',
            'storeLocaleCodes',
            'translations',
            'activeLocale'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanEdit();

        $location = MapLocation::query()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'zoom' => ['nullable', 'integer', 'min:1', 'max:21'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['nullable', 'in:0,1'],
            'image.*' => ['nullable', 'image', 'max:5120'],
        ]);
        $validator->validate();

        $content = $this->normalizeContentFromRequest($request);

        $image = $location->image;

        if ($uploaded = $this->firstUploadedMapImage($request)) {
            $this->deleteStoredImageIfLocal($image);
            $image = $uploaded->store('web-map-locations/'.$location->id, 'public');
        } elseif (! $request->has('image')) {
            $this->deleteStoredImageIfLocal($image);
            $image = null;
        }

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');

        $location->update([
            'latitude' => (float) $lat,
            'longitude' => (float) $lng,
            'zoom' => $request->filled('zoom') ? (int) $request->input('zoom') : 15,
            'embed' => null,
            'sort_order' => (int) $request->input('sort_order'),
            'status' => $request->boolean('status', true),
            'content' => $content,
            'image' => $image,
        ]);

        $to = route('admin.map-locations.edit', $location->id);
        if ($request->filled('locale') && count($this->getStoreLocaleCodes()) > 1) {
            $to .= '?'.http_build_query(['locale' => (string) $request->input('locale')]);
        }

        return redirect()
            ->to($to)
            ->with('success', trans('admin::app.settings.map-locations.update-success'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->assertCanView();
        $this->assertCanDelete();

        $location = MapLocation::query()->findOrFail($id);
        $this->deleteStoredImageIfLocal($location->image);
        $location->delete();

        return redirect()
            ->route('admin.map-locations.index')
            ->with('success', trans('admin::app.settings.map-locations.delete-success'));
    }
}
