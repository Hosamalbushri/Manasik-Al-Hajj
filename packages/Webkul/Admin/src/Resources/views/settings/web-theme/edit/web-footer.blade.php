@php
    use Illuminate\Support\Facades\Storage;

    $o = is_array($opts) ? $opts : [];
    $oEditor = $o;
    $oldOpts = old('options');
    if (is_array($oldOpts)) {
        foreach ($oldOpts as $k => $v) {
            $oEditor[$k] = $v;
        }
    }

    $brand = array_merge(['icon' => 'fas fa-kaaba', 'title' => '', 'description' => '', 'trust' => '', 'logo_path' => ''], $oEditor['brand'] ?? []);
    $storedLogoPath = trim((string) old('options.brand.logo_path', $brand['logo_path'] ?? ''));
    $logoPreviewUrl = '';
    if ($storedLogoPath !== '') {
        $pub = ltrim(str_replace('storage/', '', $storedLogoPath), '/');
        if ($pub !== '' && Storage::disk('public')->exists($pub)) {
            $logoPreviewUrl = Storage::url($pub);
        }
    }
    $ce = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $oEditor['col_explore'] ?? []);
    $cs = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $oEditor['col_support'] ?? []);
    $contact = array_merge(['title' => '', 'items' => []], $oEditor['contact'] ?? []);
    $sub = array_merge([
        'title' => '', 'placeholder' => '', 'privacy' => '', 'success_msg' => '', 'invalid_msg' => '',
    ], $oEditor['subscribe'] ?? []);
    $bottom = array_merge(['copyright' => '', 'mini_nav_label' => '', 'links' => []], $oEditor['bottom'] ?? []);

    $footerUid = 1;
    $footerRow = static function (array $row, int &$uid): array {
        $out = array_merge($row, ['_uid' => $uid]);
        $uid++;

        return $out;
    };
    $nonEmptySocial = static function (array $s): bool {
        return trim((string) ($s['icon'] ?? '')) !== ''
            || trim((string) ($s['url'] ?? '')) !== ''
            || trim((string) ($s['aria_label'] ?? '')) !== '';
    };
    $nonEmptyLink = static function (array $r): bool {
        return trim((string) ($r['label'] ?? '')) !== '' || trim((string) ($r['url'] ?? '')) !== '';
    };
    $nonEmptyContact = static function (array $r): bool {
        return trim((string) ($r['icon'] ?? '')) !== '' || trim((string) ($r['text'] ?? '')) !== '';
    };

    $socialForVue = [];
    foreach (is_array($oEditor['social'] ?? null) ? $oEditor['social'] : [] as $s) {
        if (! is_array($s) || ! $nonEmptySocial($s)) {
            continue;
        }
        $socialForVue[] = $footerRow([
            'icon'        => (string) ($s['icon'] ?? ''),
            'url'         => (string) ($s['url'] ?? ''),
            'aria_label'  => (string) ($s['aria_label'] ?? ''),
        ], $footerUid);
        if (count($socialForVue) >= 8) {
            break;
        }
    }
    if ($socialForVue === []) {
        $socialForVue[] = $footerRow(['icon' => '', 'url' => '', 'aria_label' => ''], $footerUid);
    }

    $exploreLinksForVue = [];
    foreach ($ce['links'] ?? [] as $r) {
        if (! is_array($r) || ! $nonEmptyLink($r)) {
            continue;
        }
        $exploreLinksForVue[] = $footerRow([
            'label' => (string) ($r['label'] ?? ''),
            'url'   => (string) ($r['url'] ?? ''),
        ], $footerUid);
        if (count($exploreLinksForVue) >= 12) {
            break;
        }
    }
    if ($exploreLinksForVue === []) {
        $exploreLinksForVue[] = $footerRow(['label' => '', 'url' => ''], $footerUid);
    }

    $supportLinksForVue = [];
    foreach ($cs['links'] ?? [] as $r) {
        if (! is_array($r) || ! $nonEmptyLink($r)) {
            continue;
        }
        $supportLinksForVue[] = $footerRow([
            'label' => (string) ($r['label'] ?? ''),
            'url'   => (string) ($r['url'] ?? ''),
        ], $footerUid);
        if (count($supportLinksForVue) >= 12) {
            break;
        }
    }
    if ($supportLinksForVue === []) {
        $supportLinksForVue[] = $footerRow(['label' => '', 'url' => ''], $footerUid);
    }

    $contactItemsForVue = [];
    foreach ($contact['items'] ?? [] as $r) {
        if (! is_array($r) || ! $nonEmptyContact($r)) {
            continue;
        }
        $contactItemsForVue[] = $footerRow([
            'icon' => (string) ($r['icon'] ?? ''),
            'text' => (string) ($r['text'] ?? ''),
        ], $footerUid);
        if (count($contactItemsForVue) >= 6) {
            break;
        }
    }
    if ($contactItemsForVue === []) {
        $contactItemsForVue[] = $footerRow(['icon' => '', 'text' => ''], $footerUid);
    }

    $bottomLinksForVue = [];
    foreach ($bottom['links'] ?? [] as $r) {
        if (! is_array($r) || ! $nonEmptyLink($r)) {
            continue;
        }
        $bottomLinksForVue[] = $footerRow([
            'label' => (string) ($r['label'] ?? ''),
            'url'   => (string) ($r['url'] ?? ''),
        ], $footerUid);
        if (count($bottomLinksForVue) >= 10) {
            break;
        }
    }
    if ($bottomLinksForVue === []) {
        $bottomLinksForVue[] = $footerRow(['label' => '', 'url' => ''], $footerUid);
    }

    $footerUidNext = $footerUid;
    $fx = array_merge(['back_to_top' => true], $oEditor['effects'] ?? []);
    $visibility = array_merge([
        'brand' => true,
        'social' => true,
        'explore' => true,
        'support' => true,
        'contact' => true,
        'subscribe' => true,
        'bottom' => true,
        'bottom_mini' => true,
    ], is_array($oEditor['visibility'] ?? null) ? $oEditor['visibility'] : []);

    $footerColorsFromOpts = array_merge(
        ['primary' => '#d4af37', 'secondary' => '#0d2a1a'],
        is_array($oEditor['colors'] ?? null) ? $oEditor['colors'] : []
    );
    $footerColorPrimary = old('options.colors.primary', $footerColorsFromOpts['primary']);
    $footerColorSecondary = old('options.colors.secondary', $footerColorsFromOpts['secondary']);
@endphp

<v-web-footer-editor :errors="errors">
    <x-admin::shimmer.settings.themes.static-content />
</v-web-footer-editor>

@pushOnce('scripts')
    <script type="text/x-template" id="v-web-footer-editor-template">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex flex-wrap items-center justify-between gap-x-2.5 gap-y-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.create.type.web-footer')
                        </p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.content-panel')
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100">
        @lang('admin::app.settings.web-theme.edit.web-footer-info')
    </div>

                <div class="pt-4 text-sm font-medium text-gray-500">
                    <div class="mb-4 flex flex-wrap gap-4 border-b-2 pt-2">
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'general' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'general'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-tab-general')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'brand' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'brand'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-brand')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'social' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'social'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-social')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'explore' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'explore'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-col-explore')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'support' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'support'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-col-support')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'contact' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'contact'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-contact')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'subscribe' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'subscribe'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-subscribe')
                        </button>
                        <button
                            type="button"
                            class="px-2.5 pb-3.5 text-base font-medium transition dark:text-gray-300"
                            :class="activeTab === 'bottom' ? '-mb-px border-b-2 border-blue-600 text-gray-800 dark:text-white' : 'cursor-pointer text-gray-600'"
                            @click="activeTab = 'bottom'"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-bottom')
                        </button>
                    </div>
    </div>

                <div v-show="activeTab === 'general'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-tab-general')</p>
                        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-colors-hint')</p>
                        <div class="mb-6 grid gap-4 sm:grid-cols-2">
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-theme-color-primary')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="color" name="options[colors][primary]" :value="$footerColorPrimary" />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-theme-color-secondary')</x-admin::form.control-group.label>
                                <x-admin::form.control-group.control type="color" name="options[colors][secondary]" :value="$footerColorSecondary" />
                            </x-admin::form.control-group>
                        </div>
                        <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">@lang('admin::app.settings.web-theme.edit.web-footer-effects')</p>
        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[effects][back_to_top]" value="0">
            <input type="checkbox" name="options[effects][back_to_top]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.effects.back_to_top', $fx['back_to_top'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-footer-back-top')
        </label>
                    </div>
    </div>

                <div v-show="activeTab === 'brand'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-brand')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][brand]" value="0">
                            <input type="checkbox" name="options[visibility][brand]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.brand', $visibility['brand'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>

                        <input type="hidden" name="options[brand][logo_path]" :value="effectiveLogoPath">

                        <div class="mb-4">
                            <label class="mb-1.5 block text-sm font-medium text-gray-800 dark:text-white">
                                @lang('admin::app.settings.web-theme.edit.web-footer-brand-logo')
                            </label>
                            <input
                                type="file"
                                name="options[brand][logo_image]"
                                accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-gray-800"
                                @change="onFooterLogoFileChange"
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
                                @change="onFooterRemoveLogoChange"
                            >
                            @lang('admin::app.settings.web-theme.edit.web-header-remove-logo')
                        </label>

        <div class="grid gap-4 sm:grid-cols-2">
                            <div v-show="showBrandIconTitle" class="contents">
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-icon')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][icon]" :value="old('options.brand.icon', $brand['icon'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-title')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][title]" :value="old('options.brand.title', $brand['title'])" />
            </x-admin::form.control-group>
                            </div>
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-desc')</x-admin::form.control-group.label>
                <textarea name="options[brand][description]" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ old('options.brand.description', $brand['description']) }}</textarea>
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-trust')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][trust]" :value="old('options.brand.trust', $brand['trust'])" />
            </x-admin::form.control-group>
                        </div>
        </div>
    </div>

                <div v-show="activeTab === 'social'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-social')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][social]" value="0">
                            <input type="checkbox" name="options[visibility][social]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.social', $visibility['social'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-hint')</p>
                            <button
                                type="button"
                                class="secondary-button text-sm"
                                @click="addSocialRow"
                                :disabled="socialRows.length >= maxSocial"
                            >
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add')
                            </button>
                        </div>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-footer-repeater-drag"
                            :list="socialRows"
                            item-key="_uid"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                                    <button
                                        type="button"
                                        class="web-footer-repeater-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300"
                                        :aria-label="repeaterDragLabel"
                                    >
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1 grid gap-3 sm:grid-cols-3">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">
                                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.web-theme.edit.web-brand-icon')
                                            </label>
                                            <input
                                                type="text"
                                                :name="'options[social][' + index + '][icon]'"
                                                v-model="element.icon"
                                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                            >
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</label>
                                            <input
                                                type="text"
                                                :name="'options[social][' + index + '][url]'"
                                                v-model="element.url"
                                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                            >
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-aria')</label>
                                            <input
                                                type="text"
                                                :name="'options[social][' + index + '][aria_label]'"
                                                v-model="element.aria_label"
                                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                            >
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40"
                                        @click="removeSocialRow(index)"
                                    >
                                        @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                                    </button>
                                </div>
                            </template>
                        </draggable>
            </div>
    </div>

                <div v-show="activeTab === 'explore'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-col-explore')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][explore]" value="0">
                            <input type="checkbox" name="options[visibility][explore]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.explore', $visibility['explore'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
            <label class="mb-4 flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[col_explore][show_chevron]" value="0">
                            <input type="checkbox" name="options[col_explore][show_chevron]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.col_explore.show_chevron', $ce['show_chevron'] ?? true))>
                @lang('admin::app.settings.web-theme.edit.web-footer-chevron')
            </label>
            <x-admin::form.control-group class="!mb-4">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-col-title')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="text" name="options[col_explore][title]" :value="old('options.col_explore.title', $ce['title'])" />
            </x-admin::form.control-group>
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-links-hint')</p>
                            <button type="button" class="secondary-button text-sm" @click="addExploreLinkRow" :disabled="exploreLinkRows.length >= maxExploreLinks">
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add-link')
                            </button>
                        </div>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-footer-repeater-drag"
                            :list="exploreLinkRows"
                            item-key="_uid"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                                    <button type="button" class="web-footer-repeater-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300" :aria-label="repeaterDragLabel">
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1 grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.web-theme.edit.web-footer-repeater-link-label')</label>
                                            <input type="text" :name="'options[col_explore][links][' + index + '][label]'" v-model="element.label" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</label>
                                            <input type="text" :name="'options[col_explore][links][' + index + '][url]'" v-model="element.url" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <button type="button" class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" @click="removeExploreLinkRow(index)">
                                        @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                                    </button>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>

                <div v-show="activeTab === 'support'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-col-support')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][support]" value="0">
                            <input type="checkbox" name="options[visibility][support]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.support', $visibility['support'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
                        <label class="mb-4 flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[col_support][show_chevron]" value="0">
                            <input type="checkbox" name="options[col_support][show_chevron]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.col_support.show_chevron', $cs['show_chevron'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-chevron')
                        </label>
                        <x-admin::form.control-group class="!mb-4">
                            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-col-title')</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="text" name="options[col_support][title]" :value="old('options.col_support.title', $cs['title'])" />
                        </x-admin::form.control-group>
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-links-hint')</p>
                            <button type="button" class="secondary-button text-sm" @click="addSupportLinkRow" :disabled="supportLinkRows.length >= maxSupportLinks">
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add-link')
                            </button>
                        </div>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-footer-repeater-drag"
                            :list="supportLinkRows"
                            item-key="_uid"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                                    <button type="button" class="web-footer-repeater-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300" :aria-label="repeaterDragLabel">
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1 grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.web-theme.edit.web-footer-repeater-link-label')</label>
                                            <input type="text" :name="'options[col_support][links][' + index + '][label]'" v-model="element.label" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</label>
                                            <input type="text" :name="'options[col_support][links][' + index + '][url]'" v-model="element.url" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <button type="button" class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" @click="removeSupportLinkRow(index)">
                                        @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                                    </button>
                                </div>
                            </template>
                        </draggable>
                    </div>
        </div>

                <div v-show="activeTab === 'contact'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-contact')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][contact]" value="0">
                            <input type="checkbox" name="options[visibility][contact]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.contact', $visibility['contact'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-col-title')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[contact][title]" :value="old('options.contact.title', $contact['title'])" />
        </x-admin::form.control-group>
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-contact-hint')</p>
                            <button type="button" class="secondary-button text-sm" @click="addContactItemRow" :disabled="contactItemRows.length >= maxContactItems">
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add-line')
                            </button>
                        </div>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-footer-repeater-drag"
                            :list="contactItemRows"
                            item-key="_uid"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                                    <button type="button" class="web-footer-repeater-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300" :aria-label="repeaterDragLabel">
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1 grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.web-theme.edit.web-brand-icon')</label>
                                            <input type="text" :name="'options[contact][items][' + index + '][icon]'" v-model="element.icon" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-contact-text')</label>
                                            <input type="text" :name="'options[contact][items][' + index + '][text]'" v-model="element.text" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <button type="button" class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" @click="removeContactItemRow(index)">
                                        @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                                    </button>
                                </div>
                            </template>
                        </draggable>
            </div>
    </div>

                <div v-show="activeTab === 'subscribe'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-subscribe')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][subscribe]" value="0">
                            <input type="checkbox" name="options[visibility][subscribe]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.subscribe', $visibility['subscribe'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-sub-title')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[subscribe][title]" :value="old('options.subscribe.title', $sub['title'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-sub-placeholder')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[subscribe][placeholder]" :value="old('options.subscribe.placeholder', $sub['placeholder'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-sub-privacy')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[subscribe][privacy]" :value="old('options.subscribe.privacy', $sub['privacy'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-sub-ok')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[subscribe][success_msg]" :value="old('options.subscribe.success_msg', $sub['success_msg'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-sub-bad')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[subscribe][invalid_msg]" :value="old('options.subscribe.invalid_msg', $sub['invalid_msg'])" />
            </x-admin::form.control-group>
                        </div>
        </div>
    </div>

                <div v-show="activeTab === 'bottom'" class="pt-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-bottom')</p>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][bottom]" value="0">
                            <input type="checkbox" name="options[visibility][bottom]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.bottom', $visibility['bottom'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-section')
                        </label>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-copyright')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[bottom][copyright]" :value="old('options.bottom.copyright', $bottom['copyright'])" />
            <p class="mt-1 text-xs text-gray-500">@lang('admin::app.settings.web-theme.edit.web-footer-copyright-hint')</p>
        </x-admin::form.control-group>
                        <label class="mb-4 flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
                            <input type="hidden" name="options[visibility][bottom_mini]" value="0">
                            <input type="checkbox" name="options[visibility][bottom_mini]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.visibility.bottom_mini', $visibility['bottom_mini'] ?? true))>
                            @lang('admin::app.settings.web-theme.edit.web-footer-show-mini-links')
                        </label>
                        <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-show-mini-links-hint')</p>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-mini-aria')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[bottom][mini_nav_label]" :value="old('options.bottom.mini_nav_label', $bottom['mini_nav_label'])" />
        </x-admin::form.control-group>
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-links-hint')</p>
                            <button type="button" class="secondary-button text-sm" @click="addBottomLinkRow" :disabled="bottomLinkRows.length >= maxBottomLinks">
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add-link')
                            </button>
                        </div>
                        <draggable
                            class="flex flex-col gap-3"
                            ghost-class="draggable-ghost"
                            v-bind="{ animation: 200 }"
                            handle=".web-footer-repeater-drag"
                            :list="bottomLinkRows"
                            item-key="_uid"
                        >
                            <template #item="{ element, index }">
                                <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                                    <button type="button" class="web-footer-repeater-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300" :aria-label="repeaterDragLabel">
                                        <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                                    </button>
                                    <div class="min-w-0 flex-1 grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.web-theme.edit.web-footer-repeater-link-label')</label>
                                            <input type="text" :name="'options[bottom][links][' + index + '][label]'" v-model="element.label" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</label>
                                            <input type="text" :name="'options[bottom][links][' + index + '][url]'" v-model="element.url" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <button type="button" class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" @click="removeBottomLinkRow(index)">
                                        @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                                    </button>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>
            </div>
    </div>
    </script>

    <script type="module">
        app.component('v-web-footer-editor', {
            template: '#v-web-footer-editor-template',
            props: ['errors'],
            data() {
                return {
                    activeTab: 'general',
                    logoPreviewUrl: @json($logoPreviewUrl),
                    storedLogoPath: @json($storedLogoPath),
                    hasStoredLogo: @json($storedLogoPath !== ''),
                    pendingLogoUpload: false,
                    removeLogoChecked: false,
                    footerUidNext: @json($footerUidNext),
                    socialRows: @json($socialForVue),
                    exploreLinkRows: @json($exploreLinksForVue),
                    supportLinkRows: @json($supportLinksForVue),
                    contactItemRows: @json($contactItemsForVue),
                    bottomLinkRows: @json($bottomLinksForVue),
                    maxSocial: 8,
                    maxExploreLinks: 12,
                    maxSupportLinks: 12,
                    maxContactItems: 6,
                    maxBottomLinks: 10,
                    repeaterDragLabel: @json(__('admin::app.settings.web-theme.edit.web-footer-repeater-drag')),
                };
            },
            computed: {
                effectiveLogoPath() {
                    if (this.removeLogoChecked) {
                        return '';
                    }

                    return this.storedLogoPath || '';
                },
                showBrandIconTitle() {
                    if (this.pendingLogoUpload) {
                        return false;
                    }
                    if (this.removeLogoChecked) {
                        return true;
                    }
                    if (this.hasStoredLogo && this.logoPreviewUrl) {
                        return false;
                    }

                    return true;
                },
            },
            methods: {
                onFooterLogoFileChange(e) {
                    const f = e.target?.files?.[0];
                    this.pendingLogoUpload = !!(f && f.name);
                    if (this.pendingLogoUpload) {
                        this.removeLogoChecked = false;
                    }
                },
                onFooterRemoveLogoChange(e) {
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
                nextFooterUid() {
                    const id = this.footerUidNext;
                    this.footerUidNext += 1;

                    return id;
                },
                addSocialRow() {
                    if (this.socialRows.length >= this.maxSocial) {
                        return;
                    }
                    this.socialRows.push({
                        _uid: this.nextFooterUid(),
                        icon: '',
                        url: '',
                        aria_label: '',
                    });
                },
                removeSocialRow(index) {
                    if (this.socialRows.length <= 1) {
                        return;
                    }
                    this.socialRows.splice(index, 1);
                },
                addExploreLinkRow() {
                    if (this.exploreLinkRows.length >= this.maxExploreLinks) {
                        return;
                    }
                    this.exploreLinkRows.push({
                        _uid: this.nextFooterUid(),
                        label: '',
                        url: '',
                    });
                },
                removeExploreLinkRow(index) {
                    if (this.exploreLinkRows.length <= 1) {
                        return;
                    }
                    this.exploreLinkRows.splice(index, 1);
                },
                addSupportLinkRow() {
                    if (this.supportLinkRows.length >= this.maxSupportLinks) {
                        return;
                    }
                    this.supportLinkRows.push({
                        _uid: this.nextFooterUid(),
                        label: '',
                        url: '',
                    });
                },
                removeSupportLinkRow(index) {
                    if (this.supportLinkRows.length <= 1) {
                        return;
                    }
                    this.supportLinkRows.splice(index, 1);
                },
                addContactItemRow() {
                    if (this.contactItemRows.length >= this.maxContactItems) {
                        return;
                    }
                    this.contactItemRows.push({
                        _uid: this.nextFooterUid(),
                        icon: '',
                        text: '',
                    });
                },
                removeContactItemRow(index) {
                    if (this.contactItemRows.length <= 1) {
                        return;
                    }
                    this.contactItemRows.splice(index, 1);
                },
                addBottomLinkRow() {
                    if (this.bottomLinkRows.length >= this.maxBottomLinks) {
                        return;
                    }
                    this.bottomLinkRows.push({
                        _uid: this.nextFooterUid(),
                        label: '',
                        url: '',
                    });
                },
                removeBottomLinkRow(index) {
                    if (this.bottomLinkRows.length <= 1) {
                        return;
                    }
                    this.bottomLinkRows.splice(index, 1);
                },
            },
        });
    </script>
@endPushOnce
