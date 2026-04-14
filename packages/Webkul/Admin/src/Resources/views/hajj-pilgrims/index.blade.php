<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.hajj-pilgrims.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="hajj_pilgrims" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.hajj-pilgrims.index.title')
                </div>

                <p class="max-w-3xl text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.hajj-pilgrims.index.info')
                </p>
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.manasik-hajj-users.index')" />
    </div>
</x-admin::layouts>
