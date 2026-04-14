{!! view_render_event('admin.dashboard.index.manasik_top_hajj.before') !!}

@if ($manasikModuleAvailable ?? false)
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.dashboard.index.manasik.top-hajj-title')
            </p>
            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.dashboard.index.manasik.top-hajj-hint')
            </p>
        </div>

        @if (count($topHajjUsers ?? []) > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-[480px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950/50">
                            <th class="px-4 py-2.5 font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.manasik.top-hajj-rank')
                            </th>
                            <th class="px-4 py-2.5 font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.manasik.top-hajj-name')
                            </th>
                            <th class="px-4 py-2.5 font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.manasik.top-hajj-email')
                            </th>
                            <th class="px-4 py-2.5 text-end font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.manasik.top-hajj-completions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topHajjUsers as $i => $row)
                            <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                <td class="px-4 py-3 tabular-nums text-gray-500 dark:text-gray-400">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $row['name'] }}
                                </td>
                                <td class="max-w-[220px] truncate px-4 py-3 text-gray-600 dark:text-gray-300" title="{{ $row['email'] }}">
                                    {{ $row['email'] }}
                                </td>
                                <td class="px-4 py-3 text-end tabular-nums font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($row['completions']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="px-4 py-6 text-sm text-gray-500 dark:text-gray-400">
                @lang('admin::app.dashboard.index.manasik.top-hajj-empty')
            </p>
        @endif
    </div>
@endif

{!! view_render_event('admin.dashboard.index.manasik_top_hajj.after') !!}
