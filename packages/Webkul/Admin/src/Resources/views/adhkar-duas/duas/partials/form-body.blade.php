@props([
    'mode' => 'create',
    'dua' => null,
    'storeLocaleCodes' => [],
    'activeLocale' => 'en',
    'translations' => [],
    'sections' => null,
    'prefillSectionId' => 0,
])

@php
    $isEdit = $mode === 'edit' && $dua !== null;
    $sections = $sections ?? collect();

    $storeLocaleLabels = [];
    foreach (core()->storeLocales() as $localeRow) {
        $code = strtolower((string) ($localeRow['value'] ?? ''));
        $title = (string) ($localeRow['title'] ?? strtoupper($code));
        if ($code !== '') {
            $storeLocaleLabels[$code] = $title;
        }
    }

    $adminDirection = in_array(strtolower(app()->getLocale()), ['ar', 'fa', 'he', 'ur', 'ku', 'dv'], true) ? 'rtl' : 'ltr';
    $duaRepo = app(\Webkul\Web\Repositories\WebDuaRepository::class);
    $adminLoc = strtolower(app()->getLocale());
@endphp

@if (count($storeLocaleCodes) > 1)
    <input
        type="hidden"
        name="locale"
        value="{{ $activeLocale }}"
    >
@endif

<div class="flex flex-col gap-4">
    <div
        class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
    >
        <div class="flex flex-col gap-2">
            @if ($isEdit)
                <x-admin::breadcrumbs
                    name="adhkar_duas.duas.edit"
                    :entity="$dua"
                />
            @else
                <x-admin::breadcrumbs name="adhkar_duas.duas.create" />
            @endif

            <div class="text-xl font-bold dark:text-white">
                @if ($isEdit)
                    @lang('admin::app.settings.duas.edit.title')
                @else
                    @lang('admin::app.settings.duas.create.title')
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-x-2.5 gap-y-2">
            @if ($isEdit && bouncer()->hasPermission('adhkar_duas.duas.create'))
                <a
                    href="{{ route('admin.adhkar-duas.duas.create') }}"
                    class="secondary-button whitespace-nowrap"
                >
                    @lang('admin::app.settings.duas.form.add-new-dua')
                </a>
            @endif

            <a
                href="{{ route('admin.adhkar-duas.duas.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.settings.duas.form.back')
            </a>

            <button
                type="submit"
                class="primary-button"
            >
                @lang('admin::app.settings.duas.form.save')
            </button>
        </div>
    </div>

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

    <div class="flex gap-2.5 max-xl:flex-wrap">
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
                        @include('admin::adhkar-duas.duas._form-translations', [
                            'storeLocaleCodes' => $storeLocaleCodes,
                            'translations' => $translations,
                            'activeLocale' => $activeLocale,
                        ])
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>

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
                    <div class="flex flex-col gap-2">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.duas.form.section')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="web_dua_section_id"
                                rules="required"
                                :value="old('web_dua_section_id', $isEdit ? (string) $dua->web_dua_section_id : ((int) $prefillSectionId > 0 ? (string) $prefillSectionId : ''))"
                                :label="trans('admin::app.settings.duas.form.section')"
                            >
                                <option value="">@lang('admin::app.settings.duas.form.section-placeholder')</option>
                                @foreach ($sections as $sec)
                                    @php
                                        $c = is_array($sec->content) ? $sec->content : [];
                                        $secLabel = $duaRepo->resolveSectionTitle($c, $adminLoc);
                                        if ($secLabel === '') {
                                            $secLabel = (string) $sec->slug;
                                        }
                                    @endphp
                                    <option value="{{ $sec->id }}" v-pre>
                                        {{ $secLabel }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="web_dua_section_id" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.duas.form.sort-order')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="sort_order"
                                rules="required|numeric"
                                :value="old('sort_order', $isEdit ? (string) $dua->sort_order : '0')"
                                :label="trans('admin::app.settings.duas.form.sort-order')"
                            />
                            <x-admin::form.control-group.error control-name="sort_order" />
                        </x-admin::form.control-group>

                        <input type="hidden" name="status" value="0">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                @if (old('status', $isEdit ? ($dua->status ? '1' : '0') : '1') === '1')
                                    checked
                                @endif
                            >
                            @lang('admin::app.settings.duas.form.status-active')
                        </label>
                    </div>
                </x-slot>
            </x-admin::accordion>
        </div>
    </div>
</div>
