<v-hero-slides-editor :errors="errors">
    <div class="box-shadow rounded bg-white p-4 text-sm text-gray-500 dark:bg-gray-900 dark:text-gray-300">
        @lang('admin::app.settings.web-theme.edit.hero.drawer-loading')
    </div>
</v-hero-slides-editor>

@pushOnce('scripts')
    <script type="text/x-template" id="v-hero-slides-editor-template">
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.web-theme.edit.hero.title')
                        </p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.web-theme.edit.hero.subtitle')
                        </p>
                    </div>

                    <button
                        type="button"
                        class="secondary-button flex items-center gap-1.5 max-sm:w-full max-sm:justify-center max-sm:text-xs"
                        @click="create"
                    >
                        @lang('admin::app.settings.web-theme.edit.hero.add-slide')
                    </button>
                </div>

                <template v-for="(deletedSlide, index) in deletedSlides" :key="'deleted_' + index">
                    <input type="hidden" :name="'deleted_slides[' + index + '][image]'" :value="deletedSlide.image">
                </template>

                <div class="grid pt-4" v-if="slides.length" v-for="(slide, index) in slides" :key="'slide_' + index">
                    <input type="file" class="hidden" :name="'options[slides][' + index + '][image]'" :ref="'slideImageInput_' + index">
                    <input type="hidden" :name="'options[slides][' + index + '][image_path]'" :value="slide.image || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][badge_icon]'" :value="slide.badge_icon || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][badge]'" :value="slide.badge || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][title]'" :value="slide.title || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][description]'" :value="slide.description || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][primary][label]'" :value="slide.primary?.label || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][primary][icon]'" :value="slide.primary?.icon || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][primary][url]'" :value="slide.primary?.url || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][secondary][label]'" :value="slide.secondary?.label || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][secondary][icon]'" :value="slide.secondary?.icon || ''">
                    <input type="hidden" :name="'options[slides][' + index + '][secondary][url]'" :value="slide.secondary?.url || ''">

                    <template v-for="statIndex in 3" :key="'stat_' + index + '_' + statIndex">
                        <input type="hidden" :name="'options[slides][' + index + '][stats][' + (statIndex - 1) + '][number]'" :value="slide.stats?.[statIndex - 1]?.number || ''">
                        <input type="hidden" :name="'options[slides][' + index + '][stats][' + (statIndex - 1) + '][label]'" :value="slide.stats?.[statIndex - 1]?.label || ''">
                    </template>

                    <div class="flex flex-wrap justify-between gap-4 py-5 max-sm:flex-col"
                        :class="{ 'border-b border-slate-300 dark:border-gray-800': index < slides.length - 1 }">
                        <div class="flex min-w-0 flex-1 items-start gap-3 max-sm:flex-col">
                            <div class="grid min-w-0 flex-1 place-content-start gap-1.5">
                                <p class="break-words text-base text-gray-700 dark:text-gray-200">
                                    <span class="font-semibold">@lang('admin::app.settings.web-theme.edit.hero.fields.title'):</span>
                                    @{{ slide.title || '-' }}
                                </p>
                                <p class="break-words text-base text-gray-700 dark:text-gray-200">
                                    <span class="font-semibold">@lang('admin::app.settings.web-theme.edit.hero.fields.badge'):</span>
                                    @{{ slide.badge || '-' }}
                                </p>
                            </div>

                            <div class="shrink-0 text-gray-600 dark:text-gray-300">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">@lang('admin::app.settings.web-theme.edit.hero.fields.image'):</p>
                                <template v-if="slide.image">
                                    <img
                                        :src="resolveSlidePreview(slide)"
                                        :alt="slide.title || 'hero-slide-image'"
                                        class="mt-2 h-24 w-36 rounded-lg border border-gray-200 object-cover shadow-sm dark:border-gray-800"
                                    >
                                </template>
                                <template v-else>
                                    <p class="mt-2 text-sm">@lang('admin::app.settings.web-theme.edit.hero.no-image')</p>
                                </template>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-start max-sm:w-full sm:items-center">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-blue-200 px-3 py-1.5 text-sm font-medium text-blue-600 transition-all hover:bg-blue-50 dark:border-blue-900/70 dark:text-blue-400 dark:hover:bg-blue-950/40"
                                    @click="edit(slide, index)"
                                >
                                    <span class="icon-edit text-2xl leading-none"></span>
                                    @lang('admin::app.settings.web-theme.edit.hero.edit')
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-red-200 px-3 py-1.5 text-sm font-medium text-red-600 transition-all hover:bg-red-50 dark:border-red-900/70 dark:text-red-400 dark:hover:bg-red-950/40"
                                    @click="remove(index)"
                                >
                                    <span class="icon-delete text-2xl leading-none"></span>
                                    @lang('admin::app.settings.web-theme.edit.hero.delete')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10" v-else>
                    <p class="text-base font-semibold text-gray-400">
                        @lang('admin::app.settings.web-theme.edit.hero.empty-title')
                    </p>
                    <p class="text-center text-gray-400">
                        @lang('admin::app.settings.web-theme.edit.hero.empty-subtitle')
                    </p>
                </div>
            </div>

            <x-admin::form v-slot="{ errors, handleSubmit }" as="div">
                <form @submit.prevent="handleSubmit($event, saveSlide)" ref="slideForm" enctype="multipart/form-data">
                    <x-admin::drawer ref="slideModal" width="50%">
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                <template v-if="!isUpdating">@lang('admin::app.settings.web-theme.edit.hero.drawer-create-title')</template>
                                <template v-else>@lang('admin::app.settings.web-theme.edit.hero.drawer-edit-title')</template>
                            </p>
                        </x-slot>

                        <x-slot:content class="!p-4 sm:!p-6">
                            <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                                <p class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    @lang('admin::app.settings.web-theme.edit.hero.sections.basic')
                                </p>
                                <div class="grid gap-4 lg:grid-cols-2">
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.badge-icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_badge_icon" v-model="selectedSlide.badge_icon" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.badge')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_badge" v-model="selectedSlide.badge" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="!mb-0 lg:col-span-2">
                                    <x-admin::form.control-group.label class="required">@lang('admin::app.settings.web-theme.edit.hero.fields.title')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_title" rules="required" v-model="selectedSlide.title" />
                                    <x-admin::form.control-group.error control-name="slide_title" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="!mb-0 lg:col-span-2">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.description')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="textarea" name="slide_description" v-model="selectedSlide.description" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="!mb-0 lg:col-span-2">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.image')</x-admin::form.control-group.label>
                                    <div
                                        ref="modalImagePicker"
                                        ::key="'image_picker_' + imagePickerKey"
                                        @change="onSlideImageSelected"
                                    >
                                        <x-admin::media.images
                                            name="slide_temp_image"
                                            ::uploaded-images="selectedSlideMediaImages"
                                            :allow-multiple="false"
                                            width="220px"
                                            height="140px"
                                        />
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                                        @lang('admin::app.settings.web-theme.edit.hero.current-image'):
                                        <template v-if="selectedSlide.image">
                                            <img
                                                :src="selectedSlideImageFile ? selectedSlideMediaImages?.[0]?.url : previewImageUrl(selectedSlide.image)"
                                                alt="selected-slide-image"
                                                class="mt-2 h-20 w-32 rounded-md border border-gray-200 object-cover dark:border-gray-800"
                                            >
                                        </template>
                                        <template v-else>@lang('admin::app.settings.web-theme.edit.hero.no-image')</template>
                                    </p>
                                </x-admin::form.control-group>
                            </div>
                            </div>

                            <div class="mt-4 rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                                <p class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    @lang('admin::app.settings.web-theme.edit.hero.sections.primary-cta')
                                </p>
                                <div class="grid gap-3 md:grid-cols-3">
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.label')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_primary_label" v-model="selectedSlide.primary.label" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_primary_icon" v-model="selectedSlide.primary.icon" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.url')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_primary_url" v-model="selectedSlide.primary.url" />
                                </x-admin::form.control-group>
                            </div>
                            </div>

                            <div class="mt-4 rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                                <p class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    @lang('admin::app.settings.web-theme.edit.hero.sections.secondary-cta')
                                </p>
                                <div class="grid gap-3 md:grid-cols-3">
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.label')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_secondary_label" v-model="selectedSlide.secondary.label" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.icon')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_secondary_icon" v-model="selectedSlide.secondary.icon" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.url')</x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control type="text" name="slide_secondary_url" v-model="selectedSlide.secondary.url" />
                                </x-admin::form.control-group>
                            </div>
                            </div>

                            <div class="mt-4 rounded-lg border border-gray-200 p-4 dark:border-gray-800">
                                <p class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    @lang('admin::app.settings.web-theme.edit.hero.sections.stats')
                                </p>
                                <div v-for="(stat, sIndex) in selectedSlide.stats" :key="'modal_stat_' + sIndex" class="mb-3 last:mb-0">
                                    <div class="grid gap-3 md:grid-cols-2">
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.stat-number') @{{ sIndex + 1 }}</x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control type="text" ::name="'slide_stat_number_' + sIndex" v-model="selectedSlide.stats[sIndex].number" />
                                    </x-admin::form.control-group>
                                    <x-admin::form.control-group class="!mb-0">
                                        <x-admin::form.control-group.label>@lang('admin::app.settings.web-theme.edit.hero.fields.stat-label') @{{ sIndex + 1 }}</x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control type="text" ::name="'slide_stat_label_' + sIndex" v-model="selectedSlide.stats[sIndex].label" />
                                    </x-admin::form.control-group>
                                </div>
                            </div>
                            </div>
                        </x-slot>

                        <x-slot:footer class="!p-4 sm:!p-5">
                            <div class="flex flex-wrap items-center justify-center gap-2 border-t border-gray-200 pt-3 dark:border-gray-800">
                                <button
                                    type="button"
                                    class="secondary-button flex items-center justify-center gap-1.5 max-sm:w-full"
                                    @click="$refs.slideModal.close()"
                                >
                                    @lang('admin::app.settings.web-theme.edit.hero.cancel')
                                </button>

                                <button
                                    type="button"
                                    class="primary-button flex items-center justify-center gap-1.5 max-sm:w-full"
                                    @click="handleSubmit($event, saveSlide)"
                                >
                                    @lang('admin::app.settings.web-theme.edit.hero.save')
                                </button>
                            </div>
                        </x-slot>
                    </x-admin::drawer>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-hero-slides-editor', {
            template: '#v-hero-slides-editor-template',
            props: ['errors'],

            data() {
                return {
                    slides: @json($opts['slides'] ?? []),
                    deletedSlides: [],
                    selectedSlide: this.emptySlide(),
                    selectedSlideImageFile: null,
                    selectedSlideOriginalImage: null,
                    selectedSlideMediaImages: [],
                    selectedSlideIndex: null,
                    isUpdating: false,
                    imagePickerKey: 0,
                };
            },

            methods: {
                emptySlide() {
                    return {
                        badge_icon: 'fas fa-kaaba',
                        badge: '',
                        title: '',
                        description: '',
                        image: '',
                        primary: { label: '', icon: '', url: '' },
                        secondary: { label: '', icon: '', url: '' },
                        stats: [{ number: '', label: '' }, { number: '', label: '' }, { number: '', label: '' }],
                    };
                },

                create() {
                    this.resetSelectedSlide();
                    this.$refs.slideModal.toggle();
                },

                edit(slide, index) {
                    this.resetSelectedSlide();
                    this.isUpdating = true;
                    this.selectedSlideIndex = index;
                    this.selectedSlide = JSON.parse(JSON.stringify(slide));
                    this.selectedSlide.primary = this.selectedSlide.primary || { label: '', icon: '', url: '' };
                    this.selectedSlide.secondary = this.selectedSlide.secondary || { label: '', icon: '', url: '' };
                    this.selectedSlide.stats = Array.isArray(this.selectedSlide.stats) ? this.selectedSlide.stats : [];
                    while (this.selectedSlide.stats.length < 3) this.selectedSlide.stats.push({ number: '', label: '' });
                    this.selectedSlideOriginalImage = this.selectedSlide.image || null;
                    this.selectedSlideMediaImages = this.selectedSlide.image
                        ? [{ id: 'existing_image', url: this.previewImageUrl(this.selectedSlide.image), is_new: 0 }]
                        : [];
                    this.imagePickerKey++;
                    this.$refs.slideModal.toggle();
                },

                onSlideImageSelected(e) {
                    const file = e.target?.files?.[0];
                    if (! file) {
                        return;
                    }

                    this.selectedSlideImageFile = file;
                    this.selectedSlideMediaImages = [{
                        id: 'new_image_preview',
                        url: URL.createObjectURL(file),
                        is_new: 1,
                    }];
                },

                previewImageUrl(path) {
                    if (! path) {
                        return '';
                    }

                    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:')) {
                        return path;
                    }

                    return path.startsWith('/') ? path : `/storage/${path}`;
                },

                setFile(file, index) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    setTimeout(() => {
                        const inputRef = this.$refs['slideImageInput_' + index];
                        const inputEl = Array.isArray(inputRef) ? inputRef[0] : inputRef;
                        if (inputEl) {
                            inputEl.files = dataTransfer.files;
                        }
                    }, 0);
                },

                resolveSlidePreview(slide) {
                    if (slide?.preview) {
                        return slide.preview;
                    }

                    return this.previewImageUrl(slide?.image || '');
                },

                saveSlide() {
                    const payload = JSON.parse(JSON.stringify(this.selectedSlide));
                    if (!payload.title || payload.title.trim() === '') {
                        return;
                    }

                    let targetIndex;

                    if (this.isUpdating && this.selectedSlideIndex !== null) {
                        this.slides[this.selectedSlideIndex] = payload;
                        targetIndex = this.selectedSlideIndex;
                    } else {
                        this.slides.push(payload);
                        targetIndex = this.slides.length - 1;
                    }

                    if (this.selectedSlideImageFile) {
                        this.setFile(this.selectedSlideImageFile, targetIndex);
                        this.slides[targetIndex].image = this.selectedSlideImageFile.name;
                        this.slides[targetIndex].preview = this.selectedSlideMediaImages?.[0]?.url || '';
                        if (this.isUpdating && this.selectedSlideOriginalImage) {
                            this.deletedSlides.push({ image: this.selectedSlideOriginalImage });
                        }
                    }

                    this.resetSelectedSlide();
                    this.$refs.slideModal.toggle();
                },

                remove(index) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            const slide = this.slides[index];
                            if (slide?.image) {
                                this.deletedSlides.push({ image: slide.image });
                            }
                            this.slides.splice(index, 1);
                        },
                    });
                },

                resetSelectedSlide() {
                    this.selectedSlide = this.emptySlide();
                    this.selectedSlideImageFile = null;
                    this.selectedSlideOriginalImage = null;
                    this.selectedSlideMediaImages = [];
                    this.selectedSlideIndex = null;
                    this.isUpdating = false;
                    this.imagePickerKey++;
                },
            },
        });
    </script>
@endPushOnce

