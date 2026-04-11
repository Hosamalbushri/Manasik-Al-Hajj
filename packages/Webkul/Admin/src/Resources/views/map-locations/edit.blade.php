<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.map-locations.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.map-locations.update', $location->id)"
        method="POST"
        enctype="multipart/form-data"
    >
        @method('PUT')

        @include('admin::map-locations.partials.form-body', [
            'mode' => 'edit',
            'location' => $location,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => $translations,
        ])
    </x-admin::form>
</x-admin::layouts>
