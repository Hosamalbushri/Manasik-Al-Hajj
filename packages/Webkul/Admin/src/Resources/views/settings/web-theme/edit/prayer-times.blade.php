@php
    $method = (int) ($opts['method'] ?? 2);
    $method = $method >= 0 && $method <= 15 ? $method : 2;
    $autoplay = (int) ($opts['autoplay_ms'] ?? 4000);
    $autoplay = $autoplay >= 1000 && $autoplay <= 60000 ? $autoplay : 4000;
    $apiUrl = (string) ($opts['api_url'] ?? '');
    $hour12 = filter_var($opts['hour12'] ?? true, FILTER_VALIDATE_BOOLEAN);
@endphp

<div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <div class="flex flex-col gap-1 border-b border-gray-200 pb-3 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.settings.web-theme.edit.prayer-times-heading')
            </p>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                @lang('admin::app.settings.web-theme.edit.prayer-times-help')
            </p>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-api-url-field')
                </x-admin::form.control-group.label>
                <textarea
                    name="options[api_url]"
                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 font-mono text-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    rows="3"
                    placeholder="{{ trans('admin::app.settings.web-theme.edit.prayer-times-api-url-placeholder') }}"
                >{{ old('options.api_url', $apiUrl) }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-api-url-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-title-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[heading]"
                    :value="$opts['heading'] ?? ''"
                    :placeholder="trans('admin::app.settings.web-theme.edit.prayer-times-title-placeholder')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-title-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-description-field')
                </x-admin::form.control-group.label>
                <textarea
                    name="options[description]"
                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    rows="3"
                    maxlength="500"
                    placeholder="{{ trans('admin::app.settings.web-theme.edit.prayer-times-description-placeholder') }}"
                >{{ old('options.description', $opts['description'] ?? '') }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-description-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-location-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="text"
                    name="options[location_label]"
                    :value="$opts['location_label'] ?? ''"
                    :placeholder="trans('admin::app.settings.web-theme.edit.prayer-times-location-placeholder')"
                />
            </x-admin::form.control-group>

            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                @lang('admin::app.settings.web-theme.edit.prayer-times-fallback-title')
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                @lang('admin::app.settings.web-theme.edit.prayer-times-fallback-help')
            </p>

            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>
                        @lang('admin::app.settings.web-theme.edit.prayer-times-city-field')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="options[city]"
                        :value="$opts['city'] ?? 'Makkah'"
                    />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>
                        @lang('admin::app.settings.web-theme.edit.prayer-times-country-field')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="options[country]"
                        :value="$opts['country'] ?? 'Saudi Arabia'"
                    />
                </x-admin::form.control-group>
            </div>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-method-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="number"
                    name="options[method]"
                    min="0"
                    max="15"
                    :value="$method"
                    :label="trans('admin::app.settings.web-theme.edit.prayer-times-method-field')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-method-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.settings.web-theme.edit.prayer-times-autoplay-field')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="number"
                    name="options[autoplay_ms]"
                    min="1000"
                    max="60000"
                    step="500"
                    :value="$autoplay"
                    :label="trans('admin::app.settings.web-theme.edit.prayer-times-autoplay-field')"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-autoplay-hint')
                </p>
            </x-admin::form.control-group>

            <x-admin::form.control-group class="!mb-0">
                <input type="hidden" name="options[hour12]" value="0" />
                <label class="flex cursor-pointer items-center gap-2">
                    <input
                        type="checkbox"
                        name="options[hour12]"
                        value="1"
                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                        @checked(filter_var(old('options.hour12', $hour12 ? '1' : '0'), FILTER_VALIDATE_BOOLEAN))
                    />
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        @lang('admin::app.settings.web-theme.edit.prayer-times-hour12-field')
                    </span>
                </label>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.edit.prayer-times-hour12-hint')
                </p>
            </x-admin::form.control-group>
        </div>
    </div>
</div>
