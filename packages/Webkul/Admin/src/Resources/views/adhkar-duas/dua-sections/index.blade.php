<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.dua-sections.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="adhkar_duas.dua_sections" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.dua-sections.index.title')
                </div>

                <p class="max-w-3xl text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.dua-sections.index.info')
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('adhkar_duas.dua_sections.create'))
                    <a
                        href="{{ route('admin.adhkar-duas.dua-sections.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.dua-sections.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.adhkar-duas.dua-sections.index')" />
    </div>
</x-admin::layouts>
