@props([
    'id' => 'web-modal-confirm',
])

<div
    id="{{ $id }}"
    class="web-modal-confirm fixed inset-0 z-[2000]"
    role="presentation"
    aria-hidden="true"
    data-web-modal-confirm
    data-default-title="{{ e(__('web::app.components.modal.confirm.title')) }}"
    data-default-message="{{ e(__('web::app.components.modal.confirm.message')) }}"
    data-default-btn-disagree="{{ e(__('web::app.components.modal.confirm.disagree-btn')) }}"
    data-default-btn-agree="{{ e(__('web::app.components.modal.confirm.agree-btn')) }}"
>
    <div
        class="web-modal-confirm__backdrop absolute inset-0 bg-gray-500/50"
        data-wmc-backdrop
        aria-hidden="true"
    ></div>

    <div
        class="web-modal-confirm__wrap relative z-[1] flex min-h-full items-end justify-center overflow-y-auto p-4 text-center sm:items-center sm:p-0"
        data-wmc-wrap
    >
        <div
            class="web-modal-confirm__panel w-full max-w-[475px] overflow-hidden rounded-xl bg-white p-5 text-start shadow-xl max-md:w-[90%] max-sm:p-4"
            data-wmc-panel
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $id }}-title"
            aria-describedby="{{ $id }}-message"
            tabindex="-1"
        >
            <div class="flex gap-2.5">
                <div class="shrink-0">
                    <span class="flex rounded-full border border-gray-300 p-2.5">
                        <i class="icon-error text-3xl max-sm:text-xl" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="min-w-0 flex-1">
                    <h2
                        id="{{ $id }}-title"
                        class="text-xl font-semibold text-gray-900 max-sm:text-lg"
                        data-wmc-title
                    >
                        {{ __('web::app.components.modal.confirm.title') }}
                    </h2>

                    <p
                        id="{{ $id }}-message"
                        class="pb-5 pt-1.5 text-sm text-gray-500"
                        data-wmc-message
                    >
                        {{ __('web::app.components.modal.confirm.message') }}
                    </p>

                    <div class="flex flex-wrap justify-end gap-2.5">
                        <button
                            type="button"
                            class="secondary-button max-md:py-3 max-sm:px-6 max-sm:py-2.5"
                            data-wmc-disagree
                        >
                            {{ __('web::app.components.modal.confirm.disagree-btn') }}
                        </button>

                        <button
                            type="button"
                            class="primary-button max-md:py-3 max-sm:px-6 max-sm:py-2.5"
                            data-wmc-agree
                        >
                            {{ __('web::app.components.modal.confirm.agree-btn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
