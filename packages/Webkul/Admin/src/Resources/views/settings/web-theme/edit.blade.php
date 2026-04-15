<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.web-theme.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.settings.web-theme.update', $theme->id)"
        enctype="multipart/form-data"
        method="POST"
    >
        @method('PUT')

        <div class="flex flex-col gap-4">
            {{-- Same header strip as events edit --}}
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
            >
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs
                        name="settings.web_theme.edit"
                        :entity="$theme"
                    />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.settings.web-theme.edit.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        <a
                            href="{{ route('admin.settings.web-theme.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                        >
                            @lang('admin::app.settings.web-theme.edit.back')
                        </a>

                        @if (bouncer()->hasPermission('settings.web_theme.edit'))
                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.settings.web-theme.edit.save-btn')
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $storeLocaleLabels = [];
                foreach (core()->storeLocales() as $localeRow) {
                    $code = strtolower((string) ($localeRow['value'] ?? ''));
                    $title = (string) ($localeRow['title'] ?? strtoupper($code));
                    if ($code !== '') {
                        $storeLocaleLabels[$code] = $title;
                    }
                }
            @endphp

            @php
                $adminDirection = in_array(strtolower(app()->getLocale()), ['ar', 'fa', 'he', 'ur', 'ku', 'dv'], true) ? 'rtl' : 'ltr';
            @endphp

            <div class="mt-2 flex items-center gap-x-1">
                <x-admin::dropdown
                    position="bottom-{{ $adminDirection === 'ltr' ? 'left' : 'right' }}"
                    :class="count($storeLocaleCodes) <= 1 ? 'hidden' : ''"
                >
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-language text-2xl"></span>

                            <span v-pre>{{ $storeLocaleLabels[$activeLocale] ?? strtoupper($activeLocale) }}</span>

                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $activeLocale }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <x-slot:content class="!p-0">
                        @foreach ($storeLocaleCodes as $localeCode)
                            <a
                                href="?{{ \Illuminate\Support\Arr::query(['locale' => $localeCode]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $localeCode === $activeLocale ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                                v-pre
                            >
                                {{ $storeLocaleLabels[$localeCode] ?? strtoupper($localeCode) }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>

            {{-- Same two-column body as events edit: flex row, left flex-1, right w-[500px] --}}
            <div class="flex gap-2.5 max-xl:flex-wrap">
                {{-- Left: section content in accordion (title in header, same pattern as details column) --}}
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <x-admin::accordion class="rounded-lg">
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.content-panel')
                                </p>
                            </div>
                        </x-slot>

                        <x-slot:content>
                            <div class="flex flex-col gap-2">
                                @includeWhen($theme->type === 'static_content', 'admin::settings.web-theme.edit.static-content', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'image_carousel', 'admin::settings.web-theme.edit.image-carousel', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'immersive_hero', 'admin::settings.web-theme.edit.immersive-hero', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'supplications_content', 'admin::settings.web-theme.edit.supplications-content', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'web_header', 'admin::settings.web-theme.edit.web-header', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'web_footer', 'admin::settings.web-theme.edit.web-footer', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'inner_page_hero', 'admin::settings.web-theme.edit.inner-page-hero', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                    'innerHeroHeaderOpts' => $innerHeroHeaderOpts ?? [],
                                ])

                                @includeWhen($theme->type === 'section_divider', 'admin::settings.web-theme.edit.section-divider', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'maps_showcase', 'admin::settings.web-theme.edit.maps-showcase', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'manasik_showcase', 'admin::settings.web-theme.edit.manasik-showcase', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                                @includeWhen($theme->type === 'prayer_times', 'admin::settings.web-theme.edit.prayer-times', [
                                    'theme' => $theme,
                                    'opts' => $opts,
                                ])

                            </div>
                        </x-slot>
                    </x-admin::accordion>
                </div>

                {{-- Right: details in accordion (same as events edit sidebar) --}}
                <div class="flex w-[500px] max-w-full flex-col gap-2 max-sm:w-full">
                    <x-admin::accordion class="rounded-lg">
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.general')
                                </p>
                            </div>
                        </x-slot>

                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.web-theme.edit.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    rules="required"
                                    :value="$theme->name"
                                    :label="trans('admin::app.settings.web-theme.edit.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.web-theme.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="sort_order"
                                    rules="required|numeric"
                                    :value="$theme->sort_order"
                                    :label="trans('admin::app.settings.web-theme.edit.sort-order')"
                                />

                                <x-admin::form.control-group.error control-name="sort_order" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.web-theme.edit.type')
                                </x-admin::form.control-group.label>

                                <input
                                    type="hidden"
                                    name="type"
                                    value="{{ $theme->type }}"
                                >

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="type_display"
                                    :value="$theme->type"
                                    disabled
                                    class="cursor-not-allowed opacity-70"
                                >
                                    <option value="image_carousel">@lang('admin::app.settings.web-theme.create.type.image-carousel')</option>
                                    <option value="static_content">@lang('admin::app.settings.web-theme.create.type.static-content')</option>
                                    <option value="immersive_hero">@lang('admin::app.settings.web-theme.create.type.immersive-hero')</option>
                                    <option value="supplications_content">@lang('admin::app.settings.web-theme.create.type.supplications-content')</option>
                                    <option value="web_header">@lang('admin::app.settings.web-theme.create.type.web-header')</option>
                                    <option value="web_footer">@lang('admin::app.settings.web-theme.create.type.web-footer')</option>
                                    <option value="inner_page_hero">@lang('admin::app.settings.web-theme.create.type.inner-page-hero')</option>
                                    <option value="section_divider">@lang('admin::app.settings.web-theme.create.type.section-divider')</option>
                                    <option value="maps_showcase">@lang('admin::app.settings.web-theme.create.type.maps-showcase')</option>
                                    <option value="manasik_showcase">@lang('admin::app.settings.web-theme.create.type.manasik-showcase')</option>
                                    <option value="prayer_times">@lang('admin::app.settings.web-theme.create.type.prayer-times')</option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="type" />
                            </x-admin::form.control-group>

                            <input type="hidden" name="theme_code" value="{{ config('web.storefront_theme_code', 'web') }}">

                            <x-admin::form.control-group class="!mb-0">
                                @if (in_array($theme->type, ['web_header', 'web_footer'], true))
                                    <input type="hidden" name="status" value="{{ $theme->status ? '1' : '0' }}">
                                    <div class="pointer-events-none select-none opacity-60">
                                        <label class="flex cursor-not-allowed items-center gap-2.5 text-sm text-gray-800 dark:text-gray-200">
                                            <input
                                                type="checkbox"
                                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                                disabled
                                                {{ $theme->status ? 'checked' : '' }}
                                            >
                                            <span>@lang('admin::app.settings.web-theme.edit.status-active')</span>
                                        </label>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                        @lang('admin::app.settings.web-theme.edit.status-active-locked-hint')
                                    </p>
                                @else
                                    <input
                                        type="hidden"
                                        name="status"
                                        value="0"
                                    >

                                    <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-800 dark:text-gray-200">
                                        <input
                                            type="checkbox"
                                            name="status"
                                            value="1"
                                            class="rounded border-gray-300 text-brandColor focus:ring-brandColor dark:border-gray-600 dark:bg-gray-900"
                                            {{ $theme->status ? 'checked' : '' }}
                                        >

                                        <span>@lang('admin::app.settings.web-theme.edit.status-active')</span>
                                    </label>
                                @endif
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
