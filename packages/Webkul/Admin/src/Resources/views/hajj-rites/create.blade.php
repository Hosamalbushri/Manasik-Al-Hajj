<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.hajj-rites.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.manasik-hajj-rites.store')" method="POST">
        @include('admin::hajj-rites.partials.form-body', [
            'mode' => 'create',
            'rite' => null,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => [],
            'duas' => $duas,
            'linkedDuaIdsString' => '',
        ])
    </x-admin::form>
</x-admin::layouts>
