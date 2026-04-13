@props([
    'flash' => [],
])

@php
    $type = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';

    $typeStyles = [
        'success' => [
            'container' => 'background: #D4EDDA',
            'message' => 'color: #155721',
            'icon' => 'color: #155721',
        ],
        'error' => [
            'container' => 'background: #F8D7DA',
            'message' => 'color: #721C24',
            'icon' => 'color: #721C24',
        ],
        'warning' => [
            'container' => 'background: #FFF3CD',
            'message' => 'color: #856404',
            'icon' => 'color: #856404',
        ],
        'info' => [
            'container' => 'background: #E2E3E5',
            'message' => 'color: #383D41',
            'icon' => 'color: #383D41',
        ],
    ];

    $s = $typeStyles[$type] ?? $typeStyles['info'];

    $iconClass = match ($type) {
        'success' => 'icon-toast-done',
        'error' => 'icon-toast-error',
        'warning' => 'icon-toast-exclamation-mark',
        default => 'icon-toast-info',
    };
@endphp

<div
    data-shop-flash
    data-auto-dismiss="5000"
    class="web-flash-group-item flex w-max max-w-[408px] justify-between gap-12 rounded-lg px-5 py-3 max-sm:max-w-80 max-sm:items-center max-sm:gap-2 max-sm:p-3"
    style="{{ $s['container'] }}"
>
    <p
        class="flex items-center break-words text-sm"
        style="{{ $s['message'] }}"
    >
        <span
            class="{{ $iconClass }} text-2xl ltr:mr-2.5 rtl:ml-2.5"
            style="{{ $s['icon'] }}"
            aria-hidden="true"
        ></span>
        <span>{!! nl2br(e(is_string($message) ? $message : (string) $message)) !!}</span>
    </p>

    <span
        role="button"
        tabindex="0"
        data-shop-flash-dismiss
        class="icon-cancel max-h-4 max-w-4 shrink-0 cursor-pointer"
        style="{{ $s['icon'] }}"
        aria-label="{{ __('web::app.components.layouts.flash-group.close') }}"
    ></span>
</div>
