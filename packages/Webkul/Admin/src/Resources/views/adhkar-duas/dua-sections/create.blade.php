<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.dua-sections.create.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.adhkar-duas.dua-sections.store')"
        method="POST"
    >
        @include('admin::adhkar-duas.dua-sections.partials.form-body', [
            'mode' => 'create',
            'section' => null,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => [],
        ])
    </x-admin::form>
</x-admin::layouts>
