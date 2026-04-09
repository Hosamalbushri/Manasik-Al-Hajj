<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\LocaleDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Models\Locale;
use Webkul\Core\Repositories\LocaleRepository;

class LocaleController extends Controller
{
    public const STORE_DEFAULT_LOCALE_CODE = 'general.general.locale_settings.locale';

    public function __construct(protected LocaleRepository $localeRepository) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(LocaleDataGrid::class)->process();
        }

        return view('admin::settings.locales.index');
    }

    public function website(): View
    {
        if (
            ! bouncer()->hasPermission('settings.locales')
            && ! bouncer()->hasPermission('settings.locales.website')
        ) {
            abort(403);
        }

        $locales = Locale::query()->orderBy('name')->get();

        $codes = $locales->pluck('code')->all();

        $defaultLocale = core()->getConfigData(self::STORE_DEFAULT_LOCALE_CODE)
            ?? config('app.locale', 'en');

        if ($codes !== [] && ! in_array($defaultLocale, $codes, true)) {
            $defaultLocale = $codes[0];
        }

        return view('admin::settings.locales.website', compact('locales', 'defaultLocale'));
    }

    public function updateWebsite(): RedirectResponse
    {
        if (! bouncer()->hasPermission('settings.locales.edit')) {
            abort(403);
        }

        $this->validate(request(), [
            'default_locale' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', 'exists:locales,code'],
            'visible' => ['required', 'array', 'min:1'],
            'visible.*' => ['string', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', 'exists:locales,code'],
        ]);

        $visible = array_values(array_unique(array_map('strtolower', request('visible'))));
        $defaultLocale = strtolower(request('default_locale'));

        if (! in_array($defaultLocale, $visible, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'default_locale' => trans('admin::app.settings.locales.website.default-not-visible'),
                ]);
        }

        Event::dispatch('settings.locale.website.before', [$visible, $defaultLocale]);

        DB::transaction(function () use ($visible, $defaultLocale) {
            Locale::query()->update(['store_enabled' => false]);

            Locale::query()->whereIn('code', $visible)->update(['store_enabled' => true]);

            $config = CoreConfig::query()->firstOrNew(['code' => self::STORE_DEFAULT_LOCALE_CODE]);
            $config->value = $defaultLocale;
            $config->save();
        });

        Event::dispatch('settings.locale.website.after', [$visible, $defaultLocale]);

        session()->flash('success', trans('admin::app.settings.locales.website.save-success'));

        return redirect()->route('admin.settings.locales.website');
    }

    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code' => ['required', 'string', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', 'unique:locales,code'],
            'name' => ['required', 'string', 'max:255'],
            'direction' => ['required', 'in:ltr,rtl'],
        ]);

        Event::dispatch('settings.locale.create.before');

        $locale = $this->localeRepository->create([
            'code' => strtolower(request('code')),
            'name' => request('name'),
            'direction' => request('direction'),
            'store_enabled' => false,
            'admin_enabled' => false,
        ]);

        Event::dispatch('settings.locale.create.after', $locale);

        return new JsonResponse([
            'message' => trans('admin::app.settings.locales.index.create-success'),
        ]);
    }

    public function edit(int $id): JsonResponse
    {
        $locale = $this->localeRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $locale,
        ]);
    }

    public function update(int $id): JsonResponse
    {
        /** @var Locale $locale */
        $locale = $this->localeRepository->findOrFail($id);

        $this->validate(request(), [
            'name' => ['required', 'string', 'max:255'],
            'direction' => ['required', 'in:ltr,rtl'],
        ]);

        Event::dispatch('settings.locale.update.before', $id);

        $this->localeRepository->update([
            'name' => request('name'),
            'direction' => request('direction'),
        ], $id);

        Event::dispatch('settings.locale.update.after', $locale);

        return new JsonResponse([
            'message' => trans('admin::app.settings.locales.index.update-success'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        /** @var Locale $locale */
        $locale = $this->localeRepository->findOrFail($id);

        if ($locale->store_enabled && $this->countStoreEnabled() <= 1) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.locales.index.last-store-error'),
            ], 422);
        }

        Event::dispatch('settings.locale.delete.before', $id);

        $this->localeRepository->delete($id);

        Event::dispatch('settings.locale.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.settings.locales.index.destroy-success'),
        ]);
    }

    protected function countStoreEnabled(): int
    {
        return Locale::query()->where('store_enabled', true)->count();
    }
}
