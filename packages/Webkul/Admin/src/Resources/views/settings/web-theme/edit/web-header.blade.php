@php
    use Illuminate\Support\Facades\Storage;
    use Webkul\Web\Support\WebHeaderPrimaryTabs;

    $o = is_array($opts) ? $opts : [];
    $brand = array_merge(['icon' => 'fas fa-kaaba', 'title' => '', 'subtitle' => '', 'logo_path' => ''], $o['brand'] ?? []);
    $legacyNav = is_array($o['nav'] ?? null) ? $o['nav'] : [];

    $oEditor = $o;
    $oldOpts = old('options');
    if (is_array($oldOpts)) {
        if (isset($oldOpts['nav_primary'])) {
            $oEditor['nav_primary'] = $oldOpts['nav_primary'];
        }
        if (isset($oldOpts['nav_secondary'])) {
            $oEditor['nav_secondary'] = $oldOpts['nav_secondary'];
        }
    }

    $navPrimaryRowsForVue = WebHeaderPrimaryTabs::editorRowsFromOptions($oEditor, $legacyNav);

    $navSecondary = [];
    if (isset($oEditor['nav_secondary']) && is_array($oEditor['nav_secondary'])) {
        foreach (range(0, 2) as $i) {
            $navSecondary[] = [
                'label' => (string) ($oEditor['nav_secondary'][$i]['label'] ?? ''),
                'url'   => (string) ($oEditor['nav_secondary'][$i]['url'] ?? ''),
            ];
        }
    } else {
        foreach (range(4, 6) as $i) {
            $navSecondary[] = [
                'label' => (string) ($legacyNav[$i]['label'] ?? ''),
                'url'   => (string) ($legacyNav[$i]['url'] ?? ''),
            ];
        }
    }

    $storedLogoPath = trim((string) old('options.brand.logo_path', $brand['logo_path'] ?? ''));
    $logoPreviewUrl = '';
    if ($storedLogoPath !== '') {
        $pub = ltrim(str_replace('storage/', '', $storedLogoPath), '/');
        if ($pub !== '' && Storage::disk('public')->exists($pub)) {
            $logoPreviewUrl = Storage::url($pub);
        }
    }

    $headerColorsFromOpts = array_merge(
        ['primary' => '#1f6e2f', 'secondary' => '#2c8e3c'],
        is_array($o['colors'] ?? null) ? $o['colors'] : []
    );
    $headerColorPrimary = old('options.colors.primary', $headerColorsFromOpts['primary']);
    $headerColorSecondary = old('options.colors.secondary', $headerColorsFromOpts['secondary']);
@endphp

<v-web-header-editor :errors="errors">
    <x-admin::shimmer.settings.themes.static-content />
</v-web-header-editor>

@pushOnce('scripts')
    <script type="text/x-template" id="v-web-header-editor-template">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex flex-wrap items-center justify-between gap-x-2.5 gap-y-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.create.type.web-header')
                        </p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.content-panel')
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100">
                    @lang('admin::app.settings.web-theme.edit.web-header-info')
                </div>

                <div class="pt-4 text-sm font-medium text-gray-500">
                    <div class="mb-4 flex flex-wrap gap-4 border-b-2 pt-2">
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'brand' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'brand'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-brand')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'nav_primary' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'nav_primary'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-nav-primary')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'nav_secondary' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'nav_secondary'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-nav-secondary')
                        </button>
                    </div>
                </div>

                <div v-show="activeTab === 'brand'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-brand')</p>

                        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-header-colors-hint')</p>
                        <div class="mb-6 grid gap-4 sm:grid-cols-2">
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-theme-color-primary')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="color" name="options[colors][primary]" :value="$headerColorPrimary" />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-theme-color-secondary')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="color" name="options[colors][secondary]" :value="$headerColorSecondary" />
                            </x-admin::form.control-group>
                        </div>

                        <input type="hidden" name="options[brand][logo_path]" :value="effectiveLogoPath">

                        <div class="mb-4">
                            <label class="mb-1.5 block text-sm font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.web-theme.edit.web-header-logo')
                            </label>
                            <input
                                type="file"
                                name="options[brand][logo_image]"
                                accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-gray-800"
                                @change="onLogoFileChange"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                @lang('admin::app.settings.web-theme.edit.web-header-logo-help')
                            </p>
                        </div>

                        <div v-show="hasStoredLogo && !removeLogoChecked && logoPreviewUrl" class="mb-4">
                            <p class="mb-1 text-xs font-medium text-gray-600 dark:text-gray-300">@lang('admin::app.settings.web-theme.edit.web-header-logo-current')</p>
                            <img :src="logoPreviewUrl" alt="" class="h-12 max-w-[180px] rounded border border-gray-200 object-contain dark:border-gray-700">
                        </div>

                        <label v-show="hasStoredLogo && !pendingLogoUpload" class="mb-4 flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input
                                type="checkbox"
                                name="options[brand][remove_logo]"
                                value="1"
                                class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900"
                                @change="onRemoveLogoChange"
                            >
                            @lang('admin::app.settings.web-theme.edit.web-header-remove-logo')
                        </label>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-icon')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="text" name="options[brand][icon]" :value="old('options.brand.icon', $brand['icon'])" />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-title')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="text" name="options[brand][title]" :value="old('options.brand.title', $brand['title'])" />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-subtitle')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="text" name="options[brand][subtitle]" :value="old('options.brand.subtitle', $brand['subtitle'])" />
                            </x-admin::form.control-group>
                        </div>
                    </div>
                </div>

                <div v-show="activeTab === 'nav_primary'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-1 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-nav-primary')</p>
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-nav-primary-help')</p>
                        <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-nav-primary-reorder-hint')</p>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-header-primary-drag-handle"
                            :list="navPrimaryRows"
                            item-key="pageKey"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded border border-gray-100 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/50">
                                    <button
                                        type="button"
                                        class="web-header-primary-drag-handle mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300"
                                        :aria-label="primaryDragLabel"
                                    >
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1">
                                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            @{{ element.slotTitle }}
                                        </p>
                                        <input type="hidden" :name="'options[nav_primary][' + index + '][page_key]'" :value="element.pageKey">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-800 dark:text-white">
                                            @lang('admin::app.settings.web-theme.edit.web-nav-primary-label-field')
                                        </label>
                                        <input
                                            type="text"
                                            :name="'options[nav_primary][' + index + '][label]'"
                                            v-model="element.label"
                                            class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm font-normal text-gray-800 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                        >
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>

                <div v-show="activeTab === 'nav_secondary'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-1 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-nav-secondary')</p>
                        <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-nav-secondary-help')</p>
                        @foreach ($navSecondary as $i => $row)
                            <div class="mb-3 grid gap-3 border-b border-gray-100 pb-3 last:mb-0 last:border-0 last:pb-0 dark:border-gray-800 sm:grid-cols-2">
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-nav-secondary-label', ['n' => $i + 1])</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" :name="'options[nav_secondary]['.$i.'][label]'" :value="old('options.nav_secondary.'.$i.'.label', $row['label'] ?? '')" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-nav-secondary-url', ['n' => $i + 1])</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" :name="'options[nav_secondary]['.$i.'][url]'" :value="old('options.nav_secondary.'.$i.'.url', $row['url'] ?? '')" />
                                </x-admin::form.control-group>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-web-header-editor', {
            template: '#v-web-header-editor-template',
            props: ['errors'],
            data() {
                return {
                    activeTab: 'brand',
                    logoPreviewUrl: @json($logoPreviewUrl),
                    storedLogoPath: @json($storedLogoPath),
                    hasStoredLogo: @json($storedLogoPath !== ''),
                    pendingLogoUpload: false,
                    removeLogoChecked: false,
                    navPrimaryRows: @json($navPrimaryRowsForVue),
                    primaryDragLabel: @json(__('admin::app.settings.web-theme.edit.web-nav-primary-drag')),
                };
            },
            computed: {
                effectiveLogoPath() {
                    if (this.removeLogoChecked) {
                        return '';
                    }

                    return this.storedLogoPath || '';
                },
            },
            methods: {
                onLogoFileChange(e) {
                    const f = e.target?.files?.[0];
                    this.pendingLogoUpload = !!(f && f.name);
                    if (this.pendingLogoUpload) {
                        this.removeLogoChecked = false;
                    }
                },
                onRemoveLogoChange(e) {
                    this.removeLogoChecked = !! e.target.checked;
                    if (this.removeLogoChecked) {
                        this.pendingLogoUpload = false;
                        this.hasStoredLogo = false;
                        this.storedLogoPath = '';
                        this.logoPreviewUrl = '';
                    } else {
                        this.hasStoredLogo = @json($storedLogoPath !== '');
                        this.storedLogoPath = @json($storedLogoPath);
                        this.logoPreviewUrl = @json($logoPreviewUrl);
                    }
                },
            },
        });
    </script>
@endPushOnce
