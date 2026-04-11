<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.web-theme.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="settings.web_theme" />

                <div class="text-xl font-bold dark:text-gray-300">
                    @lang('admin::app.settings.web-theme.index.title')
                </div>

                <p class="max-w-3xl text-xs text-gray-500 dark:text-gray-400">
                    @lang('admin::app.settings.web-theme.index.info')
                </p>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('settings.web_theme.create'))
                    {{-- Native button: @click must bind on a real element; v-button does not forward listeners here. --}}
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.webThemeCreate.openModal()"
                    >
                        @lang('admin::app.settings.web-theme.index.create-btn')
                    </button>

                    <v-create-web-theme-form ref="webThemeCreate" />
                @endif
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.settings.web-theme.index')" />
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-web-theme-form-template"
        >
            <div>
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <x-admin::modal ref="themeCreateModal">
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('admin::app.settings.web-theme.create.title')
                                </p>
                            </x-slot>

                            <x-slot:content>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.web-theme.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.web-theme.create.name')"
                                        :placeholder="trans('admin::app.settings.web-theme.create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.web-theme.create.sort-order')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required|numeric"
                                        :label="trans('admin::app.settings.web-theme.create.sort-order')"
                                        :placeholder="trans('admin::app.settings.web-theme.create.sort-order')"
                                    />

                                    <x-admin::form.control-group.error control-name="sort_order" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.web-theme.create.type.title')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        value="image_carousel"
                                    >
                                        <option
                                            v-for="(type, key) in themeTypes"
                                            :value="key"
                                            :text="type"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                <input type="hidden" name="theme_code" value="{{ config('web.storefront_theme_code', 'web') }}">
                            </x-slot>

                            <x-slot:footer>
                                <x-admin::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('admin::app.settings.web-theme.create.save-btn')"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-web-theme-form', {
                template: '#v-create-web-theme-form-template',

                data() {
                    return {
                        themeTypes: {
                            image_carousel: "@lang('admin::app.settings.web-theme.create.type.image-carousel')",
                            static_content: "@lang('admin::app.settings.web-theme.create.type.static-content')",
                            immersive_hero: "@lang('admin::app.settings.web-theme.create.type.immersive-hero')",
                            web_header: "@lang('admin::app.settings.web-theme.create.type.web-header')",
                            web_footer: "@lang('admin::app.settings.web-theme.create.type.web-footer')",
                            inner_page_hero: "@lang('admin::app.settings.web-theme.create.type.inner-page-hero')",
                        },

                        isLoading: false,
                    };
                },

                methods: {
                    openModal() {
                        this.$refs.themeCreateModal.toggle();
                    },

                    create(params, { setErrors }) {
                        this.isLoading = true;

                        this.$axios.post('{{ route('admin.settings.web-theme.store') }}', params)
                            .then((response) => {
                                this.isLoading = false;

                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                }
                            })
                            .catch((error) => {
                                this.isLoading = false;

                                if (error.response?.status === 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
