<v-static-content :errors="errors">
    <x-admin::shimmer.settings.themes.static-content />
</v-static-content>

<!-- Static Content Vue Component -->
@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-static-content-template"
    >
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.static-heading')
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.static-help')
                        </p>
                    </div>

                    <div
                        class="flex gap-2.5"
                        v-if="isHtmlEditorActive"
                    >
                        <label
                            class="secondary-button"
                            for="static_image"
                        >
                            @lang('admin::app.settings.web-theme.edit.add-image')
                        </label>

                        <input
                            type="file"
                            name="static_image"
                            id="static_image"
                            class="hidden"
                            accept="image/*"
                            ref="static_image"
                            label="Image"
                            @change="storeImage($event)"
                        >
                    </div>
                </div>

                <div class="pt-4 text-center text-sm font-medium text-gray-500">
                    <div class="tabs">
                        <div class="mb-4 flex gap-4 border-b-2 pt-2 max-sm:hidden">
                            <p @click="switchEditor('v-html-editor-theme', true)">
                                <div
                                    class="cursor-pointer px-2.5 pb-3.5 text-base font-medium text-gray-600 transition dark:text-gray-300"
                                    :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-html-editor-theme'}"
                                >
                                    HTML
                                </div>
                            </p>

                            <p @click="switchEditor('v-css-editor-theme', false);">
                                <div
                                    class="cursor-pointer px-2.5 pb-3.5 text-base font-medium text-gray-600 transition dark:text-gray-300"
                                    :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-css-editor-theme'}"
                                >
                                    CSS
                                </div>
                            </p>

                            <p @click="switchEditor('v-static-content-previewer', false);">
                                <div
                                    class="cursor-pointer px-2.5 pb-3.5 text-base font-medium text-gray-600 transition dark:text-gray-300"
                                    :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-static-content-previewer'}"
                                >
                                    @lang('admin::app.settings.web-theme.edit.preview')
                                </div>
                            </p>
                        </div>
                    </div>
                </div>

                <input
                    type="hidden"
                    name="options[html]"
                    v-model="options.html"
                />

                <input
                    type="hidden"
                    name="options[css]"
                    v-model="options.css"
                />

                <KeepAlive class="[&>*]:dark:bg-gray-900 [&>*]:dark:!text-white">
                    <component
                        :is="inittialEditor"
                        ref="editor"
                        @editor-data="editorData"
                        :options="options"
                    >
                    </component>
                </KeepAlive>
            </div>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-html-editor-theme-template"
    >
        <div ref="html"></div>
    </script>

    <script
        type="text/x-template"
        id="v-css-editor-theme-template"
    >
        <div ref="css"></div>
    </script>

    <script
        type="text/x-template"
        id="v-static-content-previewer-template"
    >
        <div v-html="getPreviewContent()"></div>
    </script>

    <script type="module">
        app.component('v-static-content', {
            template: '#v-static-content-template',

            props: ['errors'],

            data() {
                return {
                    inittialEditor: 'v-html-editor-theme',

                    options: @json($opts ?? null),

                    isHtmlEditorActive: true,
                };
            },

            created() {
                if (this.options === null) {
                    this.options = {};
                }

                this.options.html = this.options.html || '';
                this.options.css = this.options.css || '';
            },

            mounted() {
                this.applydarkColor();
            },

            methods: {
                switchEditor(editor, isActive) {
                    this.inittialEditor = editor;
                    this.isHtmlEditorActive = !! isActive;

                    this.$nextTick(() => {
                        this.applydarkColor();
                    });
                },

                editorData(value) {
                    if (Object.prototype.hasOwnProperty.call(value, 'html')) {
                        this.options.html = value.html;
                    } else if (Object.prototype.hasOwnProperty.call(value, 'css')) {
                        this.options.css = value.css;
                    }
                },

                storeImage($event) {
                    let imageInput = this.$refs.static_image;

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: 'Only image files are allowed.'
                        });

                        imageInput.value = '';

                        return;
                    }

                    this.$refs.editor.storeImage($event);
                },

                applydarkColor() {
                    this.$nextTick(() => {
                        const codeMirrorGutters = this.$el.querySelector('.CodeMirror-gutters');

                        if (codeMirrorGutters) {
                            codeMirrorGutters.classList.add('dark:bg-gray-900', 'dark:!text-white');
                        }
                    });
                },
            },
        });
    </script>

    <script type="module">
        app.component('v-html-editor-theme', {
            template: '#v-html-editor-theme-template',

            props: ['options'],

            data() {
                return {
                    cursorPointer: {},
                };
            },

            created() {
                this.initHtmlEditor();

                this.$emitter.on('change-theme', (theme) => this._html.setOption('theme', (theme === 'dark') ? 'ayu-dark' : 'default'));
            },

            methods: {
                initHtmlEditor() {
                    this.$nextTick(() => {
                        this.options.html = SimplyBeautiful().html(this.options.html || '');

                        this._html = new CodeMirror(this.$refs.html, {
                            lineNumbers: true,
                            tabSize: 4,
                            lineWrapping: true,
                            lineWiseCopyCut: true,
                            value: this.options.html,
                            mode: 'htmlmixed',
                            theme: document.documentElement.classList.contains('dark') ? 'ayu-dark' : 'default',
                        });

                        this._html.on('changes', (e) => {
                            this.options.html = this._html.getValue();
                            this.cursorPointer = e.getCursor();
                            this.$emit('editorData', { html: this.options.html });
                        });
                    });
                },

                storeImage($event) {
                    let selectedImage = $event.target.files[0];

                    if (! selectedImage) {
                        return;
                    }

                    const allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/gif'];

                    if (! allowedImageTypes.includes(selectedImage.type)) {
                        return;
                    }

                    let formData = new FormData();

                    formData.append('image', selectedImage);
                    formData.append('id', '{{ $theme->id }}');
                    formData.append('type', 'static_content');

                    this.$axios.post('{{ route('admin.settings.web-theme.store') }}', formData)
                        .then((response) => {
                            let editor = this._html.getDoc();
                            let cursorPointer = editor.getCursor();

                            editor.replaceRange(`<img class="lazy" data-src="${response.data}" alt="">`, {
                                line: cursorPointer.line, ch: cursorPointer.ch
                            });

                            editor.setCursor({
                                line: cursorPointer.line, ch: cursorPointer.ch + response.data.length
                            });
                        })
                        .catch((error) => {
                            if (error.response?.status == 422) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            }
                        })
                        .finally(() => {
                            if (this.$parent?.$refs?.static_image) {
                                this.$parent.$refs.static_image.value = '';
                            }
                        });
                },
            },
        });
    </script>

    <script type="module">
        app.component('v-css-editor-theme', {
            template: '#v-css-editor-theme-template',

            props: ['options'],

            created() {
                this.initCssEditor();

                this.$emitter.on('change-theme', (theme) => this._css.setOption('theme', (theme === 'dark') ? 'ayu-dark' : 'default'));
            },

            methods: {
                initCssEditor() {
                    this.$nextTick(() => {
                        this.options.css = SimplyBeautiful().css(this.options.css || '');

                        this._css = new CodeMirror(this.$refs.css, {
                            lineNumbers: true,
                            lineWrapping: true,
                            tabSize: 4,
                            lineWiseCopyCut: true,
                            value: this.options.css,
                            mode: 'css',
                            theme: document.documentElement.classList.contains('dark') ? 'ayu-dark' : 'default',
                        });

                        this._css.on('changes', () => {
                            this.options.css = this._css.getValue();
                            this.$emit('editorData', { css: this.options.css });
                        });
                    });
                },
            },
        });
    </script>

    <script type="module">
        app.component('v-static-content-previewer', {
            template: '#v-static-content-previewer-template',

            props: ['options'],

            methods: {
                getPreviewContent() {
                    let html = String(this.options?.html || '');

                    html = html
                        .replaceAll('src=""', '')
                        .replaceAll('data-src', 'src');

                    return html + '<style type="text/css">' + String(this.options?.css || '') + '</style>';
                },
            },
        });
    </script>

    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"
    >
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simply-beautiful@latest/dist/index.min.js"></script>
@endPushOnce

@pushOnce('styles')
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css"
    >
    </link>

    <link rel="stylesheet" href="https://codemirror.net/5/theme/ayu-dark.css">
@endPushOnce
