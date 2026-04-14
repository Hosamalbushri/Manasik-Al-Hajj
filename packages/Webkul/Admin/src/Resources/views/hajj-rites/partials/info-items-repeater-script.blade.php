@pushOnce('scripts', 'hajj-rite-info-items-repeater')
    <script type="text/x-template" id="v-hajj-rite-info-items-template">
        <div class="flex flex-col gap-3">
            <div class="mb-1 flex flex-wrap items-center justify-between gap-2">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.hajj-rites.form.info-items-repeater-hint')
                </p>
                <button
                    type="button"
                    class="secondary-button text-sm"
                    @click="addRow"
                    :disabled="rows.length >= maxRows"
                >
                    @lang('admin::app.settings.web-theme.edit.web-footer-repeater-add-line')
                </button>
            </div>
            <draggable
                class="flex flex-col gap-3"
                ghost-class="draggable-ghost"
                v-bind="{ animation: 200 }"
                handle=".hajj-rite-info-drag"
                :list="rows"
                item-key="_uid"
            >
                <template #item="{ element, index }">
                    <div class="flex flex-wrap items-start gap-3 rounded-lg border border-gray-200 bg-gray-50/80 p-3 dark:border-gray-800 dark:bg-gray-950/40">
                        <button
                            type="button"
                            class="hajj-rite-info-drag mt-2 shrink-0 cursor-grab text-gray-400 hover:text-gray-600 active:cursor-grabbing dark:hover:text-gray-300"
                            :aria-label="dragLabel"
                        >
                            <span class="icon-sort-down inline-block -rotate-90 text-2xl leading-none"></span>
                        </button>
                        <div class="min-w-0 flex-1">
                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">
                                @lang('admin::app.settings.web-theme.edit.web-footer-repeater-item') @{{ index + 1 }} — @lang('admin::app.settings.hajj-rites.form.info-text')
                            </label>
                            <input
                                type="text"
                                :name="'content[translations][' + locale + '][info_items][' + index + '][text]'"
                                v-model="element.text"
                                class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            >
                        </div>
                        <button
                            type="button"
                            class="transparent-button shrink-0 px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40"
                            @click="removeRow(index)"
                        >
                            @lang('admin::app.settings.web-theme.edit.web-footer-repeater-remove')
                        </button>
                    </div>
                </template>
            </draggable>
        </div>
    </script>

    <script type="module">
        app.component('v-hajj-rite-info-items', {
            template: '#v-hajj-rite-info-items-template',

            props: {
                locale: {
                    type: String,
                    required: true,
                },
                initialRows: {
                    type: Array,
                    default: () => [],
                },
                maxRows: {
                    type: Number,
                    default: 20,
                },
            },

            data() {
                const rows = Array.isArray(this.initialRows)
                    ? this.initialRows.map((r) => ({
                        _uid: r._uid,
                        text: typeof r.text === 'string' ? r.text : '',
                    }))
                    : [];

                let uidNext = rows.reduce((m, r) => Math.max(m, r._uid || 0), 0) + 1;

                if (rows.length === 0) {
                    rows.push({ _uid: uidNext++, text: '' });
                }

                return {
                    rows,
                    uidNext,
                    dragLabel: @json(__('admin::app.settings.web-theme.edit.web-footer-repeater-drag')),
                };
            },

            methods: {
                addRow() {
                    if (this.rows.length >= this.maxRows) {
                        return;
                    }

                    this.rows.push({
                        _uid: this.uidNext++,
                        text: '',
                    });
                },

                removeRow(index) {
                    if (this.rows.length <= 1) {
                        return;
                    }

                    this.rows.splice(index, 1);
                },
            },
        });
    </script>
@endPushOnce
