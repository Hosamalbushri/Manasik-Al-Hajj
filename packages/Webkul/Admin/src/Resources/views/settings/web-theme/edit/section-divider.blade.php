@php
    $sdVariant = old('options.variant', $opts['variant'] ?? 'inset_card');
    $sdVariant = is_string($sdVariant) ? strtolower(trim($sdVariant)) : 'inset_card';
    if (! in_array($sdVariant, ['inset_card', 'full_bleed', 'content_heading', 'parchment_card'], true)) {
        $sdVariant = 'inset_card';
    }
@endphp

<v-section-divider-editor :errors="errors">
    <x-admin::shimmer.settings.themes.static-content />
</v-section-divider-editor>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-section-divider-editor-template"
    >
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex flex-wrap items-center justify-between gap-x-2.5 gap-y-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.create.type.section-divider')
                        </p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.section-divider.panel-subtitle')
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.variant-title')
                        </p>
                        <div class="mb-0">
                            <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.web-theme.edit.section-divider.variant-label')
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="options[variant]"
                                v-model="activeVariant"
                                class="custom-select w-full rounded border border-gray-200 px-2.5 py-2 text-sm font-normal text-gray-800 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                            >
                                <option value="inset_card">@lang('admin::app.settings.web-theme.edit.section-divider.variant-inset-card')</option>
                                <option value="full_bleed">@lang('admin::app.settings.web-theme.edit.section-divider.variant-full-bleed')</option>
                                <option value="content_heading">@lang('admin::app.settings.web-theme.edit.section-divider.variant-content-heading')</option>
                                <option value="parchment_card">@lang('admin::app.settings.web-theme.edit.section-divider.variant-parchment-card')</option>
                            </select>
                        </div>
                        <p class="mt-3 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.section-divider.variant-hint')
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.visibility-title')
                        </p>
                        <input type="hidden" name="options[visible]" value="0">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="options[visible]"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                {{ old('options.visible', $opts['visible'] ?? true) ? 'checked' : '' }}
                            >
                            @lang('admin::app.settings.web-theme.edit.section-divider.visible')
                        </label>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-1 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.gold-title')
                        </p>
                        <p class="mb-4 text-xs text-gray-600 dark:text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.section-divider.gold-help')
                        </p>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.gold-field')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[gold]"
                                :value="old('options.gold', $opts['gold'] ?? '#D4AF37')"
                            />
                        </x-admin::form.control-group>
                    </div>

                    <div
                        v-show="isGradientVariant"
                        class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                    >
                        <p class="mb-1 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.gradient-title')
                        </p>
                        <p class="mb-4 text-xs text-gray-600 dark:text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.section-divider.gradient-help')
                        </p>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach (['gradient_from' => '#0D2A1A', 'gradient_mid' => '#1A3A2A', 'gradient_to' => '#0D2A1A', 'wave_fill' => '#FEFAF5'] as $field => $fallback)
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>{{ trans('admin::app.settings.web-theme.edit.section-divider.color-'.$field) }}</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        :name="'options['.$field.']'"
                                        :value="old('options.'.$field, $opts[$field] ?? $fallback)"
                                    />
                                </x-admin::form.control-group>
                            @endforeach
                        </div>
                    </div>

                    <div
                        v-show="isParchmentVariant"
                        class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                    >
                        <p class="mb-1 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.parchment-bg-title')
                        </p>
                        <p class="mb-4 text-xs text-gray-600 dark:text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.section-divider.parchment-bg-help')
                        </p>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach (['parchment_start', 'parchment_mid', 'parchment_end'] as $field)
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>{{ trans('admin::app.settings.web-theme.edit.section-divider.'.$field) }}</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        :name="'options['.$field.']'"
                                        :value="old('options.'.$field, $opts[$field] ?? '')"
                                        placeholder="{{ trans('admin::app.settings.web-theme.edit.section-divider.parchment-placeholder') }}"
                                    />
                                </x-admin::form.control-group>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.badge-title')
                        </p>
                        <input type="hidden" name="options[badge_show]" value="0">
                        <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="options[badge_show]"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                {{ old('options.badge_show', $opts['badge_show'] ?? true) ? 'checked' : '' }}
                            >
                            @lang('admin::app.settings.web-theme.edit.section-divider.badge-show')
                        </label>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.badge-icon')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[badge_icon]"
                                :value="old('options.badge_icon', $opts['badge_icon'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.badge-text')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[badge_text]"
                                :value="old('options.badge_text', $opts['badge_text'] ?? '')"
                            />
                        </x-admin::form.control-group>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.heading-title')
                        </p>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.title')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[title]"
                                :value="old('options.title', $opts['title'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.description')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="options[description]"
                                rows="4"
                                :value="old('options.description', $opts['description'] ?? '')"
                            />
                        </x-admin::form.control-group>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.primary-title')
                        </p>
                        <input type="hidden" name="options[primary_show]" value="0">
                        <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="options[primary_show]"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                {{ old('options.primary_show', $opts['primary_show'] ?? false) ? 'checked' : '' }}
                            >
                            @lang('admin::app.settings.web-theme.edit.section-divider.btn-show')
                        </label>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-label')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[primary_label]"
                                :value="old('options.primary_label', $opts['primary_label'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-url')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[primary_url]"
                                :value="old('options.primary_url', $opts['primary_url'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-icon')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[primary_icon]"
                                :value="old('options.primary_icon', $opts['primary_icon'] ?? '')"
                            />
                        </x-admin::form.control-group>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.section-divider.secondary-title')
                        </p>
                        <input type="hidden" name="options[secondary_show]" value="0">
                        <label class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="options[secondary_show]"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                {{ old('options.secondary_show', $opts['secondary_show'] ?? false) ? 'checked' : '' }}
                            >
                            @lang('admin::app.settings.web-theme.edit.section-divider.btn-show')
                        </label>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-label')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[secondary_label]"
                                :value="old('options.secondary_label', $opts['secondary_label'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-url')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[secondary_url]"
                                :value="old('options.secondary_url', $opts['secondary_url'] ?? '')"
                            />
                        </x-admin::form.control-group>
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.section-divider.btn-icon')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="options[secondary_icon]"
                                :value="old('options.secondary_icon', $opts['secondary_icon'] ?? '')"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-section-divider-editor', {
            template: '#v-section-divider-editor-template',
            props: ['errors'],

            data() {
                return {
                    activeVariant: @json($sdVariant),
                };
            },

            computed: {
                isGradientVariant() {
                    return this.activeVariant === 'inset_card' || this.activeVariant === 'full_bleed';
                },

                isParchmentVariant() {
                    return this.activeVariant === 'parchment_card';
                },
            },
        });
    </script>
@endPushOnce
