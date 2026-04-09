<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.locales.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        @include('admin::settings.locales._tabs')

        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="settings.locales" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.locales.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('settings.locales.create'))
                    {{-- Native button: @click must bind on a real element; v-button does not forward listeners here (web-theme index). --}}
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.localeSettings.openModal()"
                    >
                        @lang('admin::app.settings.locales.index.create-btn')
                    </button>
                @endif
            </div>
        </div>

        <x-admin::accordion class="rounded-lg">
            <x-slot:header>
                <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.settings.locales.index.accordion-title')
                </p>
            </x-slot:header>

            <x-slot:content>
                <v-locale-settings ref="localeSettings">
                    <x-admin::shimmer.datagrid />
                </v-locale-settings>
            </x-slot:content>
        </x-admin::accordion>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="locale-settings-template"
        >
            <x-admin::datagrid
                :src="route('admin.settings.locales.index')"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950 max-lg:hidden"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <p>@{{ record.id }}</p>
                            <p>@{{ record.code }}</p>
                            <p>@{{ record.name }}</p>
                            <p>@{{ record.direction }}</p>

                            <div class="flex justify-end">
                                <a @click="selectedLocale=true; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>

                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div
                            class="hidden border-b px-4 py-4 text-black dark:border-gray-800 dark:text-gray-300 max-lg:block"
                            v-for="record in available.records"
                        >
                            <div class="mb-2 flex w-full items-center justify-end gap-2">
                                <a @click="selectedLocale=true; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                    >
                                    </span>
                                </a>

                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                    >
                                    </span>
                                </a>
                            </div>

                            <div class="grid gap-2">
                                <template v-for="column in available.columns">
                                    <div class="flex flex-wrap items-baseline gap-x-2">
                                        <span class="text-slate-600 dark:text-gray-300" v-html="column.label + ':'"></span>
                                        <span class="break-words font-medium text-slate-900 dark:text-white" v-html="record[column.index]"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            <x-admin::form
                v-slot="{ handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <x-admin::modal ref="localeUpdateAndCreateModal">
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @{{
                                    selectedLocale
                                    ? "@lang('admin::app.settings.locales.index.edit.title')"
                                    : "@lang('admin::app.settings.locales.index.create.title')"
                                }}
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.locales.index.create.code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="code"
                                    name="code"
                                    rules="required|max:20"
                                    :label="trans('admin::app.settings.locales.index.create.code')"
                                    :placeholder="trans('admin::app.settings.locales.index.create.code')"
                                    ::readonly="selectedLocale"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.locales.index.create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="name"
                                    name="name"
                                    rules="required|max:255"
                                    :label="trans('admin::app.settings.locales.index.create.name')"
                                    :placeholder="trans('admin::app.settings.locales.index.create.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.locales.index.create.direction')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="direction"
                                    name="direction"
                                    rules="required"
                                    :label="trans('admin::app.settings.locales.index.create.direction')"
                                >
                                    <option value="ltr">LTR</option>
                                    <option value="rtl">RTL</option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="direction" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <x-slot:footer>
                            <x-admin::button
                                button-type="submit"
                                class="primary-button justify-center"
                                :title="trans('admin::app.settings.locales.index.create.save-btn')"
                                ::loading="isProcessing"
                                ::disabled="isProcessing"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-locale-settings', {
                template: '#locale-settings-template',

                data() {
                    return {
                        isProcessing: false,

                        selectedLocale: false,
                    };
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
                },

                methods: {
                    openModal() {
                        this.selectedLocale = false;

                        this.$refs.localeUpdateAndCreateModal.toggle();

                        this.$nextTick(() => {
                            this.$refs.modalForm.setValues({
                                id: '',
                                code: '',
                                name: '',
                                direction: 'ltr',
                            });
                        });
                    },

                    updateOrCreate(params, {resetForm, setErrors}) {
                        this.isProcessing = true;

                        const payload = {
                            ...params,
                            _method: params.id ? 'put' : 'post',
                        };

                        this.$axios.post(
                            params.id
                                ? "{{ route('admin.settings.locales.update', ':id') }}".replace(':id', params.id)
                                : "{{ route('admin.settings.locales.store') }}",
                            payload,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            }
                        ).then(response => {
                            this.isProcessing = false;

                            this.$refs.localeUpdateAndCreateModal.toggle();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.datagrid.get();

                            resetForm();
                        }).catch(error => {
                            this.isProcessing = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors || { enabled: [error.response.data.message] });
                            }
                        });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then(response => {
                                const d = response.data.data;

                                this.$refs.modalForm.setValues({
                                    id: d.id,
                                    code: d.code,
                                    name: d.name,
                                    direction: d.direction,
                                });

                                this.$refs.localeUpdateAndCreateModal.toggle();
                            })
                            .catch(() => {});
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
