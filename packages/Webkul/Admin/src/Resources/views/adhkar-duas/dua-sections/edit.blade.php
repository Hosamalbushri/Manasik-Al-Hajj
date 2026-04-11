<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.dua-sections.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.adhkar-duas.dua-sections.update', $section->id)"
        method="POST"
    >
        @method('PUT')

        @include('admin::adhkar-duas.dua-sections.partials.form-body', [
            'mode' => 'edit',
            'section' => $section,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => $translations,
        ])
    </x-admin::form>
</x-admin::layouts>
