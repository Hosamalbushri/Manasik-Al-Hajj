<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.duas.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.adhkar-duas.duas.update', $dua->id)"
        method="POST"
    >
        @method('PUT')

        @include('admin::adhkar-duas.duas.partials.form-body', [
            'mode' => 'edit',
            'dua' => $dua,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => $translations,
            'sections' => $sections,
        ])
    </x-admin::form>
</x-admin::layouts>
