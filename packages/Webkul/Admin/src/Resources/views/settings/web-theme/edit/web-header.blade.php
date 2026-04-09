@php
    $o = is_array($opts) ? $opts : [];
    $brand = array_merge(['icon' => 'fas fa-kaaba', 'title' => '', 'subtitle' => '', 'home_url' => ''], $o['brand'] ?? []);
    $lang = array_merge(['show_switcher' => true, 'button_label' => ''], $o['lang'] ?? []);
    $login = array_merge(['show' => true, 'label' => '', 'url' => ''], $o['login'] ?? []);
    $nav = is_array($o['nav'] ?? null) ? $o['nav'] : [];
    while (count($nav) < 12) {
        $nav[] = ['label' => '', 'icon' => '', 'url' => ''];
    }
@endphp

<div class="flex flex-col gap-4">
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100">
        @lang('admin::app.settings.web-theme.edit.web-header-info')
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[enabled]" value="0">
            <input type="checkbox" name="options[enabled]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.enabled', $o['enabled'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-enabled')
        </label>
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-dir')</p>
        <select name="options[dir]" class="w-full max-w-md rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @foreach (['auto' => 'auto (by locale)', 'rtl' => 'RTL', 'ltr' => 'LTR'] as $val => $lab)
                <option value="{{ $val }}" @selected(old('options.dir', $o['dir'] ?? 'auto') === $val)>{{ $lab }}</option>
            @endforeach
        </select>
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-brand')</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-brand-icon')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][icon]" :value="old('options.brand.icon', $brand['icon'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-home-url')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[brand][home_url]" :value="old('options.brand.home_url', $brand['home_url'])" />
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

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-nav')</p>
        @foreach ($nav as $i => $row)
            <div class="mb-3 grid gap-3 border-b border-gray-100 pb-3 last:mb-0 last:border-0 last:pb-0 dark:border-gray-800 sm:grid-cols-3">
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-nav-label', ['n' => $i + 1])</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[nav]['.$i.'][label]'" :value="old('options.nav.'.$i.'.label', $row['label'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-nav-icon')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[nav]['.$i.'][icon]'" :value="old('options.nav.'.$i.'.icon', $row['icon'] ?? '')" />
                </x-admin::form.control-group>
                <x-admin::form.control-group class="!mb-0">
                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-nav-url')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control type="text" :name="'options[nav]['.$i.'][url]'" :value="old('options.nav.'.$i.'.url', $row['url'] ?? '')" />
                </x-admin::form.control-group>
            </div>
        @endforeach
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-lang')</p>
        <label class="mb-3 flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[lang][show_switcher]" value="0">
            <input type="checkbox" name="options[lang][show_switcher]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.lang.show_switcher', $lang['show_switcher'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-lang-show')
        </label>
        <x-admin::form.control-group class="!mb-0">
            <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-lang-label')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="options[lang][button_label]" :value="old('options.lang.button_label', $lang['button_label'])" />
        </x-admin::form.control-group>
    </div>

    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <p class="mb-3 text-base font-semibold text-gray-800 dark:text-white">@lang('admin::app.settings.web-theme.edit.web-login')</p>
        <label class="mb-3 flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
            <input type="hidden" name="options[login][show]" value="0">
            <input type="checkbox" name="options[login][show]" value="1" class="rounded border-gray-300 text-brandColor dark:border-gray-600 dark:bg-gray-900" @checked(old('options.login.show', $login['show'] ?? true))>
            @lang('admin::app.settings.web-theme.edit.web-login-show')
        </label>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-login-label')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[login][label]" :value="old('options.login.label', $login['label'])" />
            </x-admin::form.control-group>
            <x-admin::form.control-group class="!mb-0">
                <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.web-login-url')</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="options[login][url]" :value="old('options.login.url', $login['url'])" />
            </x-admin::form.control-group>
        </div>
    </div>
</div>
