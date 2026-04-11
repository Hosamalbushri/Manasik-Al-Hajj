@props([
    'storeLocaleCodes' => [],
    'translations' => [],
    'activeLocale' => null,
])

@php
    $translations = is_array($translations) ? $translations : [];
@endphp

@foreach ($storeLocaleCodes as $loc)
    @php
        $slice = $translations[$loc] ?? [];
        $slice = is_array($slice) ? $slice : [];
        $panelHidden = ($activeLocale ?? null) !== null && $loc !== $activeLocale;
    @endphp
    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 {{ $panelHidden ? 'hidden' : '' }}">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
            @lang('admin::app.settings.duas.form.locale-heading', ['code' => strtoupper($loc)])
        </p>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                @lang('admin::app.settings.duas.form.title')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][title]"
                :value="old('content.translations.'.$loc.'.title', $slice['title'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.settings.duas.form.text')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="textarea"
                name="content[translations][{{ $loc }}][text]"
                rows="6"
                :value="old('content.translations.'.$loc.'.text', $slice['text'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group class="!mb-0">
            <x-admin::form.control-group.label>
                @lang('admin::app.settings.duas.form.reference')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][reference]"
                :value="old('content.translations.'.$loc.'.reference', $slice['reference'] ?? '')"
            />
        </x-admin::form.control-group>
    </div>
@endforeach
