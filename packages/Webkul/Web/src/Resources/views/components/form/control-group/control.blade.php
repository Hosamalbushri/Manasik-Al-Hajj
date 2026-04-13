@props([
    'type' => 'text',
    'name' => '',
    'hajjSkin' => false,
    'hajjPasswordToggle' => false,
])

@php
    $baseInputClass = 'mb-1.5 w-full rounded-lg border border-zinc-200 px-6 py-4 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 focus:outline-none max-sm:px-4 max-md:py-3 max-sm:py-2 max-sm:text-sm';
    $invalidClass = $errors->has($name) ? 'border-red-500 hover:border-red-500 focus:border-red-500' : '';
    $extraClass = $attributes->get('class');
    $mergedClass = trim($baseInputClass.' '.$invalidClass.' '.(is_string($extraClass) ? $extraClass : ''));
    $fieldValue = old($name, $attributes->get('value'));
    $hajjErr = $errors->has($name) ? ' hajj-input--error' : '';
@endphp

@if ($hajjSkin && $type === 'password' && $hajjPasswordToggle)
    @php
        $fid = $attributes->get('id') ?: $name;
    @endphp
    <div class="password-wrapper">
        <input
            type="password"
            name="{{ $name }}"
            id="{{ $fid }}"
            value="{{ $fieldValue }}"
            placeholder="{{ $attributes->get('placeholder') }}"
            aria-label="{{ $attributes->get('aria-label') }}"
            @if ($attributes->get('aria-required') === 'true' || $attributes->get('aria-required') === true)
                aria-required="true"
            @endif
            @if ($attributes->get('autocomplete'))
                autocomplete="{{ $attributes->get('autocomplete') }}"
            @else
                autocomplete="new-password"
            @endif
            class="input-field{{ $hajjErr }}"
        >
        <button
            type="button"
            class="toggle-password"
            data-toggle-pass="{{ $fid }}"
            aria-label="{{ $attributes->get('toggle-aria-label', __('web::hajj_auth.login-form.show-password')) }}"
        >
            <i class="fas fa-eye" aria-hidden="true"></i>
        </button>
    </div>
@elseif ($hajjSkin && in_array($type, ['text', 'email', 'password', 'number', 'tel'], true))
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $fieldValue }}"
        placeholder="{{ $attributes->get('placeholder') }}"
        aria-label="{{ $attributes->get('aria-label') }}"
        @if ($attributes->has('id'))
            id="{{ $attributes->get('id') }}"
        @endif
        @if ($attributes->get('aria-required') === 'true' || $attributes->get('aria-required') === true)
            aria-required="true"
        @endif
        @if ($attributes->get('autocomplete'))
            autocomplete="{{ $attributes->get('autocomplete') }}"
        @elseif ($type === 'password')
            autocomplete="new-password"
        @endif
        class="input-field{{ $hajjErr }}"
    >
@else
    @switch($type)
        @case('hidden')
        @case('text')
        @case('email')
        @case('password')
        @case('number')
        @case('tel')
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                value="{{ $fieldValue }}"
                placeholder="{{ $attributes->get('placeholder') }}"
                aria-label="{{ $attributes->get('aria-label') }}"
                @if ($attributes->has('id'))
                    id="{{ $attributes->get('id') }}"
                @endif
                @if ($attributes->get('aria-required') === 'true' || $attributes->get('aria-required') === true)
                    aria-required="true"
                @endif
                @if ($attributes->get('autocomplete'))
                    autocomplete="{{ $attributes->get('autocomplete') }}"
                @elseif ($type === 'password')
                    autocomplete="new-password"
                @endif
                {{ $attributes->except(['value', 'placeholder', 'aria-label', 'aria-required', 'autocomplete', 'id', 'class', 'toggle-aria-label'])->merge(['class' => $mergedClass]) }}
            >
            @break

        @case('checkbox')
            @php
                $cid = $attributes->get('id') ?: str_replace(['[', ']'], '_', $name);
                $val = $attributes->get('value', '1');
                $isChecked = old($name) !== null ? (string) old($name) === (string) $val : (bool) $attributes->get('checked', false);
            @endphp
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $cid }}"
                value="{{ $val }}"
                class="peer hidden"
                @checked($isChecked)
            >

            <label
                class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                for="{{ $cid }}"
            ></label>
            @break

        @default
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => $mergedClass]) }}
            >
    @endswitch
@endif
