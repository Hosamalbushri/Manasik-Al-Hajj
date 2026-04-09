<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.locales.website.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        @include('admin::settings.locales._tabs')

        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="settings.locales.website" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.locales.website.title')
                </div>

                <p class="max-w-3xl text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.locales.website.info')
                </p>
            </div>
        </div>

        <x-admin::form
            method="PUT"
            :action="route('admin.settings.locales.website.update')"
        >
            <div class="flex flex-col gap-4">
                <x-admin::accordion class="rounded-lg">
                    <x-slot:header>
                        <div class="flex flex-col gap-0.5 p-2.5">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.locales.website.section-default')
                            </p>

                            <p class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                @lang('admin::app.settings.locales.website.section-default-info')
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.locales.website.default-locale')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="default_locale"
                                id="default_locale"
                                rules="required"
                                :value="old('default_locale', $defaultLocale)"
                                :label="trans('admin::app.settings.locales.website.default-locale')"
                            >
                                @foreach ($locales as $locale)
                                    <option value="{{ $locale->code }}">
                                        {{ $locale->name }} ({{ $locale->code }})
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="default_locale" />
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>

                <x-admin::accordion class="rounded-lg">
                    <x-slot:header>
                        <div class="flex flex-col gap-0.5 p-2.5">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.locales.website.section-visible')
                            </p>

                            <p class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                @lang('admin::app.settings.locales.website.section-visible-info')
                            </p>
                        </div>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="flex flex-col gap-1">
                            @foreach ($locales as $locale)
                                @php
                                    $oldVisible = old('visible');
                                    $checked = is_array($oldVisible)
                                        ? in_array($locale->code, $oldVisible, true)
                                        : $locale->store_enabled;
                                @endphp

                                <x-admin::form.control-group class="!mb-2">
                                    <label class="mb-0 flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 px-3 py-2.5 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-950">
                                        <input
                                            type="checkbox"
                                            name="visible[]"
                                            value="{{ $locale->code }}"
                                            class="h-4 w-4 shrink-0 rounded border-gray-300 text-brandColor focus:ring-brandColor dark:border-gray-600 dark:bg-gray-900"
                                            @checked($checked)
                                        />

                                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                                            {{ $locale->name }}
                                            <span class="font-normal text-gray-500 dark:text-gray-400">
                                                ({{ $locale->code }})
                                            </span>
                                        </span>
                                    </label>
                                </x-admin::form.control-group>
                            @endforeach
                        </div>

                        @error('visible')
                            <p class="mt-2 text-xs italic text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('visible.*')
                            <p class="mt-2 text-xs italic text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </x-slot:content>
                </x-admin::accordion>

                @if (bouncer()->hasPermission('settings.locales.edit'))
                    <div class="flex justify-end">
                        {{-- Native submit: v-button does not forward type/listeners reliably for classic form POST (see web-theme edit). --}}
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.settings.locales.website.save-btn')
                        </button>
                    </div>
                @endif
            </div>
        </x-admin::form>
    </div>
</x-admin::layouts>
