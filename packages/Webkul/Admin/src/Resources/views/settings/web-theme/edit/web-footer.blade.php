@php
    $o = is_array($opts) ? $opts : [];
    $brand = array_merge(['icon' => 'fas fa-kaaba', 'title' => '', 'description' => '', 'trust' => ''], $o['brand'] ?? []);
    $social = $o['social'] ?? [];
    while (count($social) < 8) {
        $social[] = ['icon' => '', 'url' => '', 'aria_label' => ''];
    }
    $ce = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_explore'] ?? []);
    while (count($ce['links']) < 12) {
        $ce['links'][] = ['label' => '', 'url' => ''];
    }
    $cs = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_support'] ?? []);
    while (count($cs['links']) < 12) {
        $cs['links'][] = ['label' => '', 'url' => ''];
    }
    $contact = array_merge(['title' => '', 'items' => []], $o['contact'] ?? []);
    while (count($contact['items']) < 6) {
        $contact['items'][] = ['icon' => '', 'text' => ''];
    }
    $sub = array_merge([
        'title' => '', 'placeholder' => '', 'privacy' => '', 'success_msg' => '', 'invalid_msg' => '',
    ], $o['subscribe'] ?? []);
    $bottom = array_merge(['copyright' => '', 'mini_nav_label' => '', 'links' => []], $o['bottom'] ?? []);
    while (count($bottom['links']) < 10) {
        $bottom['links'][] = ['label' => '', 'url' => ''];
    }
    $fx = array_merge(['back_to_top' => true], $o['effects'] ?? []);
@endphp

<div class="flex flex-col gap-4">
    <div class="rounded-lg border border-teal-200 bg-teal-50 p-4 text-sm text-teal-900 dark:border-teal-900 dark:bg-teal-950 dark:text-teal-100">
        @lang('admin::app.settings.web-theme.edit.web-footer-info')
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[enabled]" value="0">
            <input type="checkbox" name="options[enabled]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.enabled', $o['enabled'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-enabled')
        </label>
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-effects')</p>
        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[effects][back_to_top]" value="0">
            <input type="checkbox" name="options[effects][back_to_top]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.effects.back_to_top', $fx['back_to_top'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-footer-back-top')
        </label>
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-brand')</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-icon')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][icon]" :value="old('options.brand.icon', $brand['icon'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0 sm:col-span-2">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-title')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][title]" :value="old('options.brand.title', $brand['title'])" />
            </x-admin::form.control-group>
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

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-social')</p>
        @foreach (range(0, 7) as $i)
            <div class="mb-3 grid gap-3 border-b border-gray-100 pb-3 last:mb-0 last:border-0 last:pb-0 dark:border-gray-800 sm:grid-cols-3">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-social-icon', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[social]['.$i.'][icon]'" :value="old('options.social.'.$i.'.icon', $social[$i]['icon'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-social-url', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[social]['.$i.'][url]'" :value="old('options.social.'.$i.'.url', $social[$i]['url'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-social-aria', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[social]['.$i.'][aria_label]'" :value="old('options.social.'.$i.'.aria_label', $social[$i]['aria_label'] ?? '')" />
                </x-admin::form.control-group>
            </div>
        @endforeach
    </div>

    @foreach (['col_explore' => [$ce, 'web-footer-col-explore'], 'col_support' => [$cs, 'web-footer-col-support']] as $colKey => $colMeta)
        @php [$col, $colTrans] = $colMeta; @endphp
        <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.'.$colTrans)</p>
            <label class="mb-4 flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                <input type="hidden" name="options[{{ $colKey }}][show_chevron]" value="0">
                <input type="checkbox" name="options[{{ $colKey }}][show_chevron]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old("options.$colKey.show_chevron", $col['show_chevron'] ?? true))>
                @lang('admin::app.settings.web-theme.edit.web-footer-chevron')
            </label>
            <x-admin::form.control-group class="!mb-4">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-col-title')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" :name="'options['.$colKey.'][title]'" :value="old('options.'.$colKey.'.title', $col['title'])" />
            </x-admin::form.control-group>
            @foreach (range(0, 11) as $i)
                <div class="mb-2 grid gap-3 sm:grid-cols-2">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-link-label', ['n' => $i + 1])</x-admin::form.control-group.label>
                        <x-admin::form.control-group.control type="text" :name="'options['.$colKey.'][links]['.$i.'][label]'" :value="old('options.'.$colKey.'.links.'.$i.'.label', $col['links'][$i]['label'] ?? '')" />
                    </x-admin::form.control-group>
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</x-admin::form.control-group.label>
                        <x-admin::form.control-group.control type="text" :name="'options['.$colKey.'][links]['.$i.'][url]'" :value="old('options.'.$colKey.'.links.'.$i.'.url', $col['links'][$i]['url'] ?? '')" />
                    </x-admin::form.control-group>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-contact')</p>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-col-title')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[contact][title]" :value="old('options.contact.title', $contact['title'])" />
        </x-admin::form.control-group>
        @foreach (range(0, 5) as $i)
            <div class="mb-3 grid gap-3 border-b border-gray-100 pb-3 last:mb-0 last:border-0 last:pb-0 dark:border-gray-800 sm:grid-cols-2">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-contact-icon', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[contact][items]['.$i.'][icon]'" :value="old('options.contact.items.'.$i.'.icon', $contact['items'][$i]['icon'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-contact-text')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[contact][items]['.$i.'][text]'" :value="old('options.contact.items.'.$i.'.text', $contact['items'][$i]['text'] ?? '')" />
                </x-admin::form.control-group>
            </div>
        @endforeach
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-subscribe')</p>
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

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-footer-bottom')</p>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-copyright')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[bottom][copyright]" :value="old('options.bottom.copyright', $bottom['copyright'])" />
            <p class="mt-1 text-xs text-gray-500">@lang('admin::app.settings.web-theme.edit.web-footer-copyright-hint')</p>
        </x-admin::form.control-group>
        <x-admin::form.control-group class="!mb-4">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-mini-aria')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[bottom][mini_nav_label]" :value="old('options.bottom.mini_nav_label', $bottom['mini_nav_label'])" />
        </x-admin::form.control-group>
        @foreach (range(0, 9) as $i)
            <div class="mb-2 grid gap-3 sm:grid-cols-2">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-mini-label', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[bottom][links]['.$i.'][label]'" :value="old('options.bottom.links.'.$i.'.label', $bottom['links'][$i]['label'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-footer-link-url')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[bottom][links]['.$i.'][url]'" :value="old('options.bottom.links.'.$i.'.url', $bottom['links'][$i]['url'] ?? '')" />
                </x-admin::form.control-group>
            </div>
        @endforeach
    </div>
</div>
