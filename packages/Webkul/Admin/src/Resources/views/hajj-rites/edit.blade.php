<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.hajj-rites.edit.title')
    </x-slot>

    <x-admin::form :action="route('admin.manasik-hajj-rites.update', $rite->id)" method="PUT">
        @include('admin::hajj-rites.partials.form-body', [
            'mode' => 'edit',
            'rite' => $rite,
            'storeLocaleCodes' => $storeLocaleCodes,
            'activeLocale' => $activeLocale,
            'translations' => $translations,
            'duas' => $duas,
            'linkedDuaIdsString' => $linkedDuaIdsString,
        ])
    </x-admin::form>
</x-admin::layouts>
