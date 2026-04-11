@props([
    'mode' => 'create',
    'location' => null,
    'storeLocaleCodes' => [],
    'activeLocale' => 'en',
    'translations' => [],
])

@php
    use Illuminate\Support\Facades\Storage;

    $isEdit = $mode === 'edit' && $location !== null;

    $storeLocaleLabels = [];
    foreach (core()->storeLocales() as $localeRow) {
        $code = strtolower((string) ($localeRow['value'] ?? ''));
        $title = (string) ($localeRow['title'] ?? strtoupper($code));
        if ($code !== '') {
            $storeLocaleLabels[$code] = $title;
        }
    }

    $adminDirection = in_array(strtolower(app()->getLocale()), ['ar', 'fa', 'he', 'ur', 'ku', 'dv'], true) ? 'rtl' : 'ltr';

    $mapUploadedImages = [];
    if ($isEdit) {
        $img = trim((string) ($location->image ?? ''));
        if ($img !== '') {
            $url = preg_match('#^https?://#i', $img) ? $img : Storage::url(ltrim(str_replace('storage/', '', $img), '/'));
            $mapUploadedImages = [['id' => 'image', 'url' => $url]];
        }
    }
@endphp

@if (count($storeLocaleCodes) > 1)
    <input
        type="hidden"
        name="locale"
        value="{{ $activeLocale }}"
    >
@endif

<div class="flex flex-col gap-4">
    <div
        class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
    >
        <div class="flex flex-col gap-2">
            @if ($isEdit)
                <x-admin::breadcrumbs
                    name="map_locations.edit"
                    :entity="$location"
                />
            @else
                <x-admin::breadcrumbs name="map_locations.create" />
            @endif

            <div class="text-xl font-bold dark:text-white">
                @if ($isEdit)
                    @lang('admin::app.settings.map-locations.edit.title')
                @else
                    @lang('admin::app.settings.map-locations.create.title')
                @endif
            </div>
        </div>

        <div class="flex items-center gap-x-2.5">
            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.map-locations.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('admin::app.settings.map-locations.form.back')
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.map-locations.form.save')
                </button>
            </div>
        </div>
    </div>

    <div class="mt-2 flex items-center gap-x-1">
        <x-admin::dropdown
            position="bottom-{{ $adminDirection === 'ltr' ? 'left' : 'right' }}"
            :class="count($storeLocaleCodes) <= 1 ? 'hidden' : ''"
        >
            <x-slot:toggle>
                <button
                    type="button"
                    class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                >
                    <span class="icon-language text-2xl"></span>

                    <span v-pre>{{ $storeLocaleLabels[$activeLocale] ?? strtoupper($activeLocale) }}</span>

                    <span class="icon-sort-down text-2xl"></span>
                </button>
            </x-slot>

            <x-slot:content class="!p-0">
                @foreach ($storeLocaleCodes as $localeCode)
                    <a
                        href="?{{ \Illuminate\Support\Arr::query(['locale' => $localeCode]) }}"
                        class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $localeCode === $activeLocale ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                        v-pre
                    >
                        {{ $storeLocaleLabels[$localeCode] ?? strtoupper($localeCode) }}
                    </a>
                @endforeach
            </x-slot>
        </x-admin::dropdown>
    </div>

    <div class="flex gap-2.5 max-xl:flex-wrap">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <x-admin::accordion class="rounded-lg">
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.content-panel')
                        </p>
                    </div>
                </x-slot>

                <x-slot:content>
                    <div class="flex flex-col gap-2">
                        @include('admin::map-locations._form-translations', [
                            'storeLocaleCodes' => $storeLocaleCodes,
                            'translations' => $translations,
                            'activeLocale' => $activeLocale,
                        ])
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>

        <div class="flex w-[500px] max-w-full flex-col gap-2 max-sm:w-full">
            <x-admin::accordion class="rounded-lg">
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.general')
                        </p>
                    </div>
                </x-slot>

                <x-slot:content>
                    <div class="flex flex-col gap-2">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.map-locations.form.section-coordinates')
                        </p>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.map-locations.form.latitude')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="latitude"
                                rules="required"
                                :value="old('latitude', $isEdit && $location->latitude !== null ? (string) $location->latitude : '')"
                                :label="trans('admin::app.settings.map-locations.form.latitude')"
                            />
                            <x-admin::form.control-group.error control-name="latitude" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.map-locations.form.longitude')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="longitude"
                                rules="required"
                                :value="old('longitude', $isEdit && $location->longitude !== null ? (string) $location->longitude : '')"
                                :label="trans('admin::app.settings.map-locations.form.longitude')"
                            />
                            <x-admin::form.control-group.error control-name="longitude" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.map-locations.form.zoom')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="zoom"
                                rules="numeric|min:1|max:21"
                                :value="old('zoom', $isEdit ? (string) ($location->zoom ?? 15) : '15')"
                                :label="trans('admin::app.settings.map-locations.form.zoom')"
                            />
                            <x-admin::form.control-group.error control-name="zoom" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.map-locations.form.sort-order')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="sort_order"
                                rules="required|numeric"
                                :value="old('sort_order', $isEdit ? (string) $location->sort_order : '0')"
                                :label="trans('admin::app.settings.map-locations.form.sort-order')"
                            />
                            <x-admin::form.control-group.error control-name="sort_order" />
                        </x-admin::form.control-group>

                        <input type="hidden" name="status" value="0">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                @if (old('status', $isEdit ? ($location->status ? '1' : '0') : '1') === '1')
                                    checked
                                @endif
                            >
                            @lang('admin::app.settings.map-locations.form.status-active')
                        </label>

                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                {{ $isEdit ? __('admin::app.settings.map-locations.form.image-replace') : __('admin::app.settings.map-locations.form.image') }}
                            </x-admin::form.control-group.label>
                            <x-admin::media.images
                                name="image"
                                :uploaded-images="$mapUploadedImages"
                            />
                            <x-admin::form.control-group.error control-name="image" />
                        </x-admin::form.control-group>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>
</div>
