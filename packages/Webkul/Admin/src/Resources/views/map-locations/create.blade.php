<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.map-locations.create.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.map-locations.store')"
        method="POST"
        enctype="multipart/form-data"
    >
        @include('admin::map-locations.partials.form-body', [
            'mode' => 'create',
            'location' => null,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => [],
        ])
    </x-admin::form>
</x-admin::layouts>
