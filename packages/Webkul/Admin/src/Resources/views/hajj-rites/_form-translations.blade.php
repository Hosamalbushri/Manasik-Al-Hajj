@props([
    'storeLocaleCodes' => [],
    'translations' => [],
    'activeLocale' => null,
])

@php
    $translations = is_array($translations) ? $translations : [];
@endphp

@include('admin::hajj-rites.partials.info-items-repeater-script')

@foreach ($storeLocaleCodes as $loc)
    @php
        $slice = $translations[$loc] ?? [];
        $slice = is_array($slice) ? $slice : [];
        $panelHidden = ($activeLocale ?? null) !== null && $loc !== $activeLocale;
        $items = $slice['info_items'] ?? [];
        $items = is_array($items) ? $items : [];
    @endphp
    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 {{ $panelHidden ? 'hidden' : '' }}">
        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
            @lang('admin::app.settings.hajj-rites.form.locale-heading', ['code' => strtoupper($loc)])
        </p>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.settings.hajj-rites.form.tab-label')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][tab_label]"
                :value="old('content.translations.'.$loc.'.tab_label', $slice['tab_label'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                @lang('admin::app.settings.hajj-rites.form.title')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][title]"
                :value="old('content.translations.'.$loc.'.title', $slice['title'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                @lang('admin::app.settings.hajj-rites.form.subtitle')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][subtitle]"
                :value="old('content.translations.'.$loc.'.subtitle', $slice['subtitle'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                @lang('admin::app.settings.hajj-rites.form.badge')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="text"
                name="content[translations][{{ $loc }}][badge]"
                :value="old('content.translations.'.$loc.'.badge', $slice['badge'] ?? '')"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                @lang('admin::app.settings.hajj-rites.form.description')
            </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="textarea"
                name="content[translations][{{ $loc }}][description]"
                rows="8"
                :value="old('content.translations.'.$loc.'.description', $slice['description'] ?? '')"
            />
        </x-admin::form.control-group>

        <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
            @lang('admin::app.settings.hajj-rites.form.info-items-heading')
        </p>
        @php
            $infoUid = 1;
            $infoRowsForVue = [];
            $oldInfo = old('content.translations.'.$loc.'.info_items');
            if (is_array($oldInfo)) {
                foreach ($oldInfo as $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $infoRowsForVue[] = [
                        '_uid' => $infoUid++,
                        'text' => (string) ($row['text'] ?? ''),
                    ];
                }
            } else {
                foreach ($items as $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $t = trim((string) ($row['text'] ?? ''));
                    $ic = trim((string) ($row['icon'] ?? ''));
                    if ($t === '' && $ic === '') {
                        continue;
                    }
                    $infoRowsForVue[] = [
                        '_uid' => $infoUid++,
                        'text' => (string) ($row['text'] ?? ''),
                    ];
                }
            }
            if ($infoRowsForVue === []) {
                $infoRowsForVue[] = ['_uid' => $infoUid++, 'text' => ''];
            }
        @endphp
        <div class="rounded border border-gray-100 p-3 dark:border-gray-800">
            <v-hajj-rite-info-items
                locale="{{ $loc }}"
                :initial-rows='@json($infoRowsForVue)'
            />
        </div>
    </div>
@endforeach
