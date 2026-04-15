@php
    $limit = (int) ($opts['limit'] ?? 6);
    $limit = $limit >= 1 && $limit <= 50 ? $limit : 6;
    $showMore = array_key_exists('show_more', $opts)
        ? filter_var($opts['show_more'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $moreUrl = (string) ($opts['more_url'] ?? '');
@endphp

<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="flex flex-col gap-1 border-b border-gray-200 pb-3 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.settings.web-theme.edit.manasik-showcase-heading')
            </p>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                @lang('admin::app.settings.web-theme.edit.manasik-showcase-help')
            </p>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-title-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[heading]"
                    :value="$opts['heading'] ?? ''"
                    :placeholder="trans('admin::app.settings.web-theme.edit.manasik-showcase-title-field')"
                />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-subtitle-field')
                </x-admin::form.control-group.label>
                <textarea
                    name="options[subheading]"
                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    rows="2"
                    placeholder="{{ trans('admin::app.settings.web-theme.edit.manasik-showcase-subtitle-field') }}"
                >{{ $opts['subheading'] ?? '' }}</textarea>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-limit-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="number"
                    name="options[limit]"
                    min="1"
                    max="50"
                    :value="$limit"
                    :label="trans('admin::app.settings.web-theme.edit.manasik-showcase-limit-field')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-limit-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group class="!mb-0">
                <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-800 dark:text-gray-200">
                    <input
                        type="checkbox"
                        name="options[show_more]"
                        value="1"
                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                        {{ $showMore ? 'checked' : '' }}
                    >
                    <span>@lang('admin::app.settings.web-theme.edit.manasik-showcase-show-more-field')</span>
                </label>
                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-show-more-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-more-url-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[more_url]"
                    :value="$moreUrl"
                    :placeholder="trans('admin::app.settings.web-theme.edit.manasik-showcase-more-url-placeholder')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.manasik-showcase-more-url-hint')
                </p>
            </x-admin::form.control-group>
        </div>
    </div>
</div>
