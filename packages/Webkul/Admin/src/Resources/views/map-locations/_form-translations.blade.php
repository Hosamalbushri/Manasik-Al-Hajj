@props([
    'storeLocaleCodes' => [],
    'translations' => [],
    'defaultLocaleValue' => null,
    'activeLocale' => null,
])

@php
    $translations = is_array($translations) ? $translations : [];
@endphp

@foreach ($storeLocaleCodes as $loc)
    @php
        $slice = $translations[$loc] ?? [];
        $slice = is_array($slice) ? $slice : [];
        $features = $slice['features'] ?? [];
        $featuresRaw = old('content.translations.'.$loc.'.features_raw', is_array($features) ? implode("\n", $features) : '');
        $panelHidden = ($activeLocale ?? null) !== null && $loc !== $activeLocale;
    @endphp
    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 {{ $panelHidden ? 'hidden' : '' }}">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
            @lang('admin::app.settings.map-locations.form.locale-heading', ['code' => strtoupper($loc)])
        </p>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>@lang('admin::app.settings.map-locations.form.title')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][title]"
                :value="old('content.translations.'.$loc.'.title', $slice['title'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>@lang('admin::app.settings.map-locations.form.badge')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][badge]"
                :value="old('content.translations.'.$loc.'.badge', $slice['badge'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>@lang('admin::app.settings.map-locations.form.description')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="textarea"
                name="content[translations][{{ $loc }}][description]"
                rows="3"
                :value="old('content.translations.'.$loc.'.description', $slice['description'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group class="!mb-0">
            <x-admin::form.control-group.label>@lang('admin::app.settings.map-locations.form.features')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="textarea"
                name="content[translations][{{ $loc }}][features_raw]"
                rows="5"
                :value="$featuresRaw"
            />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.settings.map-locations.form.features-hint')
            </p>
        </x-admin::form.control-group>
    </div>
@endforeach
