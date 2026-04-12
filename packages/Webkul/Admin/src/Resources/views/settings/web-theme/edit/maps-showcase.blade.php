@php
    $limit = (int) ($opts['limit'] ?? 0);
    $limit = $limit >= 0 && $limit <= 50 ? $limit : 0;
    $linkShow = array_key_exists('link_show', $opts)
        ? filter_var($opts['link_show'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $linkLabel = (string) ($opts['link_label'] ?? '');
@endphp

<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="flex flex-col gap-1 border-b border-gray-200 pb-3 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.settings.web-theme.edit.maps-showcase-heading')
            </p>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                @lang('admin::app.settings.web-theme.edit.maps-showcase-help')
            </p>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-title-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[heading]"
                    :value="$opts['heading'] ?? ''"
                    :placeholder="trans('admin::app.settings.web-theme.edit.maps-showcase-title-field')"
                />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-subtitle-field')
                </x-admin::form.control-group.label>
                <textarea
                    name="options[subheading]"
                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    rows="2"
                    placeholder="{{ trans('admin::app.settings.web-theme.edit.maps-showcase-subtitle-field') }}"
                >{{ $opts['subheading'] ?? '' }}</textarea>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-limit-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="number"
                    name="options[limit]"
                    min="0"
                    max="50"
                    :value="$limit"
                    :label="trans('admin::app.settings.web-theme.edit.maps-showcase-limit-field')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-limit-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group class="!mb-0">
                <input
                    type="hidden"
                    name="options[link_show]"
                    value="0"
                >
                <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-800 dark:text-gray-200">
                    <input
                        type="checkbox"
                        name="options[link_show]"
                        value="1"
                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                        {{ $linkShow ? 'checked' : '' }}
                    >
                    <span>@lang('admin::app.settings.web-theme.edit.maps-showcase-link-show-field')</span>
                </label>
                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-link-show-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-link-label-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[link_label]"
                    :value="$linkLabel"
                    :placeholder="trans('admin::app.settings.web-theme.edit.maps-showcase-link-label-placeholder')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.maps-showcase-link-label-hint')
                </p>
            </x-admin::form.control-group>
        </div>
    </div>
</div>
