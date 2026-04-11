@php
    use Webkul\Web\Support\InnerPageHeroOptions;
    use Webkul\Web\Support\WebHeaderPrimaryTabs;

    $innerHeroHeaderOpts = is_array($innerHeroHeaderOpts ?? null) ? $innerHeroHeaderOpts : [];
    $headerNav = is_array($innerHeroHeaderOpts['nav_primary'] ?? null) ? $innerHeroHeaderOpts['nav_primary'] : [];

    $innerHeroTabLabel = static function (string $pk) use ($headerNav): string {
        foreach ($headerNav as $row) {
            if (! is_array($row)) {
                continue;
            }

            if ((string) ($row['page_key'] ?? '') === $pk) {
                $l = trim((string) ($row['label'] ?? ''));
                if ($l !== '') {
                    return $l;
                }
            }
        }

        $slotKey = 'admin::app.settings.web-theme.edit.web-nav-primary-slot-'.$pk;
        $fallback = trans($slotKey);

        return $fallback !== $slotKey ? $fallback : ucfirst(str_replace('_', ' ', $pk));
    };

    $pageKeys = WebHeaderPrimaryTabs::innerHeroPageKeys();
    $pagesStored = is_array($opts['pages'] ?? null) ? $opts['pages'] : [];

    $def = InnerPageHeroOptions::defaults();
    $pageFieldDefaults = [];
    foreach (InnerPageHeroOptions::CONTENT_KEYS as $k) {
        $pageFieldDefaults[$k] = $def[$k] ?? '';
    }

    $oldPages = old('options.pages');
    $oldPages = is_array($oldPages) ? $oldPages : [];
@endphp

<v-inner-page-hero-editor :errors="errors">
    <x-admin::shimmer.settings.themes.static-content />
</v-inner-page-hero-editor>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-inner-page-hero-editor-template"
    >
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex flex-wrap items-center justify-between gap-x-2.5 gap-y-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.create.type.inner-page-hero')
                        </p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.content-panel')
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100">
                    <p class="mb-2 last:mb-0">
                        @lang('admin::app.settings.web-theme.edit.inner-page-hero.intro-tabs')
                    </p>
                    <p class="mb-0 font-medium">
                        @lang('admin::app.settings.web-theme.edit.inner-page-hero.breadcrumb-auto-hint')
                    </p>
                </div>

                <div class="pt-4 text-sm font-medium text-gray-500">
                    <div class="mb-4 flex flex-wrap gap-4 border-b-2 border-gray-200 pt-2 dark:border-gray-700">
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'default' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'default'"
                        >
                            @lang('admin::app.settings.web-theme.edit.inner-page-hero.tab-default')
                        </button>
                        @foreach ($pageKeys as $pk)
                            <button
                                type="button"
                                class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                                :class="activeTab === '{{ $pk }}' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                                @click="activeTab = '{{ $pk }}'"
                            >
                                {{ $innerHeroTabLabel($pk) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Global --}}
                <div
                    v-show="activeTab === 'default'"
                    class="pt-2"
                >
                    <div class="flex flex-col gap-4">
                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-visibility')
                            </p>
                            <input type="hidden" name="options[visible]" value="0">
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                                <input
                                    type="checkbox"
                                    name="options[visible]"
                                    value="1"
                                    class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                    {{ ($opts['visible'] ?? true) ? 'checked' : '' }}
                                >
                                @lang('admin::app.settings.web-theme.edit.inner-page-hero.visible')
                            </label>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-colors')
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach (['gradient_from' => '#0D2A1A', 'gradient_mid' => '#1A3A2A', 'gradient_to' => '#0D2A1A', 'gold' => '#D4AF37', 'wave_fill' => '#FEFAF5'] as $field => $fallback)
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label>{{ trans('admin::app.settings.web-theme.edit.inner-page-hero.color-'.$field) }}</x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            :name="'options['.$field.']'"
                                            :value="old('options.'.$field, $opts[$field] ?? $fallback)"
                                        />
                                    </x-admin::form.control-group>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($pageKeys as $pk)
                    @php
                        $pStored = is_array($pagesStored[$pk] ?? null) ? $pagesStored[$pk] : [];
                        $pOld = is_array($oldPages[$pk] ?? null) ? $oldPages[$pk] : [];
                        $p = array_replace($pageFieldDefaults, $pStored, $pOld);
                    @endphp
                    <div
                        v-show="activeTab === '{{ $pk }}'"
                        class="pt-2"
                    >
                        <p class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-page-content')
                            —
                            {{ $innerHeroTabLabel($pk) }}
                        </p>

                        <div class="flex flex-col gap-4">
                            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-badge')
                                </p>
                                <input type="hidden" name="options[pages][{{ $pk }}][badge_show]" value="0">
                                <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                                    <input
                                        type="checkbox"
                                        name="options[pages][{{ $pk }}][badge_show]"
                                        value="1"
                                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                        {{ ($p['badge_show'] ?? true) ? 'checked' : '' }}
                                    >
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.badge-show')
                                </label>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.badge-icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][badge_icon]"
                                        :value="old('options.pages.'.$pk.'.badge_icon', $p['badge_icon'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.badge-text')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][badge_text]"
                                        :value="old('options.pages.'.$pk.'.badge_text', $p['badge_text'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-heading')
                                </p>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.title')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][title]"
                                        :value="old('options.pages.'.$pk.'.title', $p['title'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.description')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="options[pages][{{ $pk }}][description]"
                                        rows="4"
                                    >{{ old('options.pages.'.$pk.'.description', $p['description'] ?? '') }}</x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-primary-btn')
                                </p>
                                <input type="hidden" name="options[pages][{{ $pk }}][primary_show]" value="0">
                                <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                                    <input
                                        type="checkbox"
                                        name="options[pages][{{ $pk }}][primary_show]"
                                        value="1"
                                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                        {{ ($p['primary_show'] ?? true) ? 'checked' : '' }}
                                    >
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-show')
                                </label>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-label')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][primary_label]"
                                        :value="old('options.pages.'.$pk.'.primary_label', $p['primary_label'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-url')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][primary_url]"
                                        :value="old('options.pages.'.$pk.'.primary_url', $p['primary_url'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][primary_icon]"
                                        :value="old('options.pages.'.$pk.'.primary_icon', $p['primary_icon'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.section-secondary-btn')
                                </p>
                                <input type="hidden" name="options[pages][{{ $pk }}][secondary_show]" value="0">
                                <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                                    <input
                                        type="checkbox"
                                        name="options[pages][{{ $pk }}][secondary_show]"
                                        value="1"
                                        class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                        {{ ($p['secondary_show'] ?? true) ? 'checked' : '' }}
                                    >
                                    @lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-show')
                                </label>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-label')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][secondary_label]"
                                        :value="old('options.pages.'.$pk.'.secondary_label', $p['secondary_label'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-url')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][secondary_url]"
                                        :value="old('options.pages.'.$pk.'.secondary_url', $p['secondary_url'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.inner-page-hero.btn-icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="options[pages][{{ $pk }}][secondary_icon]"
                                        :value="old('options.pages.'.$pk.'.secondary_icon', $p['secondary_icon'] ?? '')"
                                    />
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-inner-page-hero-editor', {
            template: '#v-inner-page-hero-editor-template',
            props: ['errors'],

            data() {
                return {
                    activeTab: 'default',
                };
            },
        });
    </script>
@endPushOnce
