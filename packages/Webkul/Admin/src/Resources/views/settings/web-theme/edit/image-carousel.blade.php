<v-image-carousel :errors="errors">
    <x-admin::shimmer.settings.themes.image-carousel />
</v-image-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-image-carousel-template"
    >
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.carousel-heading')
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.carousel-help')
                        </p>
                    </div>

                    <div class="secondary-button" @click="create">
                        @lang('admin::app.settings.web-theme.edit.carousel-add')
                    </div>
                </div>

                <template v-for="(deletedSlider, index) in deletedSliders" :key="'deleted_' + index">
                    <input type="hidden" :name="'deleted_sliders[' + index + '][image]'" :value="deletedSlider.image">
                </template>

                <div class="grid pt-4" v-if="sliders.images.length" v-for="(image, index) in sliders.images" :key="'slide_' + index">
                    <input type="file" class="hidden" :name="'options[' + index + '][image]'" :ref="'imageInput_' + index">
                    <input type="hidden" :name="'options[' + index + '][title]'" :value="image.title">
                    <input type="hidden" :name="'options[' + index + '][link]'" :value="image.link">
                    <input type="hidden" :name="'options[' + index + '][image_path]'" :value="image.image || ''">

                    <div class="flex flex-wrap justify-between gap-2.5 py-5"
                        :class="{ 'border-b border-slate-300 dark:border-gray-800': index < sliders.images.length - 1 }">
                        <div class="grid min-w-0 place-content-start gap-1.5">
                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.web-theme.edit.carousel-title'):
                                <span class="text-gray-600 transition-all dark:text-gray-300">@{{ image.title || '-' }}</span>
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.web-theme.edit.carousel-link'):
                                <span class="text-gray-600 transition-all dark:text-gray-300">@{{ image.link || '-' }}</span>
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.web-theme.edit.carousel-image'):
                                <span class="text-gray-600 transition-all dark:text-gray-300">
                                    <a :href="resolvePreviewUrl(image.image)" :ref="'image_' + index" target="_blank"
                                        class="text-blue-600 transition-all hover:underline ltr:ml-2 rtl:mr-2">
                                        <span :ref="'imageName_' + index">@{{ image.image || '-' }}</span>
                                    </a>
                                </span>
                            </p>
                        </div>

                        <div class="grid place-content-start gap-1 text-right">
                            <div class="flex items-center gap-x-5">
                                <p class="cursor-pointer text-blue-600 transition-all hover:underline" @click="edit(image, index)">
                                    @lang('admin::app.settings.web-theme.index.datagrid.edit')
                                </p>

                                <p class="cursor-pointer text-red-600 transition-all hover:underline" @click="remove(index)">
                                    @lang('admin::app.settings.web-theme.index.datagrid.delete')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10" v-else>
                    <img class="h-[120px] w-[120px] p-2 dark:mix-blend-exclusion dark:invert"
                        src="{{ vite()->asset('images/empty-placeholders/default.svg') }}"
                        alt="@lang('admin::app.settings.web-theme.edit.carousel-heading')">

                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.carousel-add')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.settings.web-theme.edit.carousel-help')
                        </p>
                    </div>
                </div>
            </div>

            <x-admin::form v-slot="{ errors, handleSubmit }" as="div">
                <form @submit.prevent="handleSubmit($event, saveSliderImage)" enctype="multipart/form-data" ref="createSliderForm">
                    <x-admin::modal ref="addSliderModal">
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                <template v-if="! isUpdating">@lang('admin::app.settings.web-theme.edit.carousel-add')</template>
                                <template v-else>@lang('admin::app.settings.web-theme.index.datagrid.edit')</template>
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.web-theme.edit.carousel-title')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="slider_title"
                                    rules="required"
                                    v-model="selectedSlider.title"
                                    :placeholder="`{{ trans('admin::app.settings.web-theme.edit.carousel-title') }}`"
                                />

                                <x-admin::form.control-group.error control-name="slider_title" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.web-theme.edit.carousel-link')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="slider_link"
                                    v-model="selectedSlider.link"
                                    :placeholder="`{{ trans('admin::app.settings.web-theme.edit.carousel-link') }}`"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.web-theme.edit.carousel-image')
                                </x-admin::form.control-group.label>

                                <div class="hidden">
                                    <x-admin::media.images
                                        ::key="'slider_image_hidden_' + mediaComponentKey"
                                        name="slider_image"
                                        ::uploaded-images='selectedSliderMediaImages'
                                    />
                                </div>

                                <v-media-images
                                    :key="'slider_image_' + mediaComponentKey"
                                    name="slider_image"
                                    :uploaded-images='selectedSliderMediaImages'
                                >
                                </v-media-images>

                                <x-admin::form.control-group.error control-name="slider_image" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <x-slot:footer>
                            <button type="button" class="primary-button justify-center" @click="handleSubmit($event, saveSliderImage)">
                                @lang('admin::app.settings.web-theme.edit.save-btn')
                            </button>
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-image-carousel', {
            template: '#v-image-carousel-template',

            props: ['errors'],

            data() {
                return {
                    sliders: @json($opts ?? null),
                    deletedSliders: [],
                    selectedSlider: {},
                    selectedSliderMediaImages: [],
                    selectedSliderOriginalImage: null,
                    mediaComponentKey: 0,
                    selectedSliderIndex: null,
                    isUpdating: false,
                };
            },

            created() {
                if (! this.sliders || ! this.sliders.images) {
                    this.sliders = { images: [] };
                }
            },

            methods: {
                resolvePreviewUrl(path) {
                    if (! path) {
                        return '#';
                    }

                    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:') || path.startsWith('/')) {
                        return path;
                    }

                    return `{{ asset('storage') }}/${path.replace(/^storage\//, '')}`;
                },

                saveSliderImage(_, { resetForm, setErrors }) {
                    const formData = new FormData(this.$refs.createSliderForm);
                    const sliderImage = formData.get('slider_image[]');
                    const hasUploadedImage = sliderImage instanceof File && sliderImage.name !== '';

                    try {
                        const sliderData = {
                            title: formData.get('slider_title') || '',
                            link: formData.get('slider_link') || '',
                        };

                        if (! this.hasSliderImage(formData, hasUploadedImage)) {
                            throw new Error("{{ trans('admin::app.settings.web-theme.edit.carousel-image') }} {{ trans('validation.required') }}");
                        }

                        const sliderIndex = this.upsertSlider(sliderData);

                        if (hasUploadedImage) {
                            this.setFile(sliderImage, sliderIndex);
                            this.markSliderImageForDeletion();
                        }

                        resetForm();
                        this.resetSelectedSlider();
                        this.$refs.addSliderModal.toggle();
                    } catch (error) {
                        setErrors({
                            slider_image: [error.message],
                        });
                    }
                },

                upsertSlider(sliderData) {
                    if (this.isUpdating) {
                        this.sliders.images[this.selectedSliderIndex] = {
                            ...this.sliders.images[this.selectedSliderIndex],
                            ...sliderData,
                        };

                        return this.selectedSliderIndex;
                    }

                    this.sliders.images.push(sliderData);

                    return this.sliders.images.length - 1;
                },

                markSliderImageForDeletion() {
                    if (! this.isUpdating || ! this.selectedSliderOriginalImage) {
                        return;
                    }

                    this.deletedSliders.push({
                        image: this.selectedSliderOriginalImage,
                    });
                },

                hasSliderImage(formData, hasUploadedImage) {
                    if (hasUploadedImage) {
                        return true;
                    }

                    return Array.from(formData.keys()).some((key) => key === 'slider_image[]' || key.startsWith('slider_image['));
                },

                setFile(file, index) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);

                    setTimeout(() => {
                        const imageRef = this.$refs['image_' + index];
                        const imageNameRef = this.$refs['imageName_' + index];
                        const inputRef = this.$refs['imageInput_' + index];

                        const imageEl = Array.isArray(imageRef) ? imageRef[0] : imageRef;
                        const imageNameEl = Array.isArray(imageNameRef) ? imageNameRef[0] : imageNameRef;
                        const inputEl = Array.isArray(inputRef) ? inputRef[0] : inputRef;

                        if (imageEl) {
                            imageEl.href = URL.createObjectURL(file);
                        }

                        if (imageNameEl) {
                            imageNameEl.innerHTML = file.name;
                        }

                        if (inputEl) {
                            inputEl.files = dataTransfer.files;
                        }

                        this.sliders.images[index].image = file.name;
                    }, 0);
                },

                remove(index) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            const slider = this.sliders.images[index];

                            if (! slider) {
                                return;
                            }

                            if (slider.image) {
                                this.deletedSliders.push({ image: slider.image });
                            }

                            this.sliders.images.splice(index, 1);
                        },
                    });
                },

                create() {
                    this.openSliderModal();
                },

                edit(slider, index) {
                    this.openSliderModal(slider, index);
                },

                openSliderModal(slider = null, index = null) {
                    this.resetSelectedSlider();

                    if (slider) {
                        this.isUpdating = true;
                        this.selectedSliderIndex = index;
                        this.selectedSlider = { ...slider };
                        this.selectedSliderOriginalImage = slider.image;
                        this.selectedSliderMediaImages = slider.image
                            ? [{ id: `slider_image_${index}`, url: this.resolvePreviewUrl(slider.image) }]
                            : [];
                    }

                    this.mediaComponentKey++;
                    this.$refs.addSliderModal.toggle();
                },

                resetSelectedSlider() {
                    this.selectedSlider = {};
                    this.selectedSliderMediaImages = [];
                    this.selectedSliderOriginalImage = null;
                    this.selectedSliderIndex = null;
                    this.isUpdating = false;
                },
            },
        });
    </script>
@endPushOnce
