@props([
    'method' => 'POST',
    'action' => '',
])

@php
    $method = strtoupper($method);
@endphp

<form
    method="{{ in_array($method, ['GET', 'HEAD'], true) ? $method : 'POST' }}"
    action="{{ $action }}"
    {{ $attributes }}
>
    @unless (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true))
        @csrf
    @endunless

    @if (! in_array($method, ['GET', 'POST', 'HEAD', 'OPTIONS'], true))
        @method($method)
    @endif

    {{ $slot }}
</form>
