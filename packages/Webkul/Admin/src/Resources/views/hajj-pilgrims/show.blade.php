<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.hajj-pilgrims.show.title', ['name' => $hajjUser->name])
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <x-admin::breadcrumbs name="hajj_pilgrims.show" :entity="$hajjUser" />

                <h1 class="text-xl font-bold dark:text-gray-200">
                    {{ $hajjUser->name }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hajjUser->email }}</p>
            </div>

            <a
                href="{{ route('admin.manasik-hajj-users.index') }}"
                class="secondary-button"
            >
                @lang('admin::app.settings.hajj-pilgrims.show.back')
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.settings.hajj-pilgrims.show.section-profile')
                </h2>
                <dl class="grid gap-2 text-sm">
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.phone')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $hajjUser->phone ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.show.birth-date')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $hajjUser->birth_date?->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.locale')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $hajjUser->locale ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.status')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $hajjUser->status ? __('admin::app.settings.hajj-pilgrims.datagrid.active') : __('admin::app.settings.hajj-pilgrims.datagrid.inactive') }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.show.address')</dt>
                        <dd class="max-w-md text-end font-medium text-gray-900 dark:text-gray-100">{{ $hajjUser->address ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.settings.hajj-pilgrims.show.section-activity')
                </h2>
                <dl class="grid gap-2 text-sm">
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.show.favorites-count')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $favoritesCount }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.completions')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $completionsCount }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 py-2 dark:border-gray-800">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.email-verified')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $hajjUser->email_verified_at ? core()->formatDate($hajjUser->email_verified_at) : '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('admin::app.settings.hajj-pilgrims.datagrid.created-at')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ core()->formatDate($hajjUser->created_at) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin::layouts>
