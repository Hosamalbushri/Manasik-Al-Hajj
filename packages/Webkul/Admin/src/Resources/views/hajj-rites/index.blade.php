<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.hajj-rites.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="manasik_hajj_rites" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.hajj-rites.index.title')
                </div>

                <p class="max-w-3xl text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.hajj-rites.index.info')
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('hajj_rites.create'))
                    <a href="{{ route('admin.manasik-hajj-rites.create') }}" class="primary-button">
                        @lang('admin::app.settings.hajj-rites.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.manasik-hajj-rites.index')" />
    </div>
</x-admin::layouts>
