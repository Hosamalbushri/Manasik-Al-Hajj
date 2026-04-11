<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.duas.create.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.adhkar-duas.duas.store')"
        method="POST"
    >
        @include('admin::adhkar-duas.duas.partials.form-body', [
            'mode' => 'create',
            'dua' => null,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => [],
            'sections' => $sections,
            'prefillSectionId' => $prefillSectionId ?? 0,
        ])
    </x-admin::form>
</x-admin::layouts>
