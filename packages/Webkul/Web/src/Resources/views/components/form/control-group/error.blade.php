@props([
    'name' => null,
    'controlName' => null,
])

@php
    $field = $name ?? $controlName;
@endphp

@error($field)
    <p {{ $attributes->merge(['class' => 'text-xs italic text-red-500']) }}>
        {{ $message }}
    </p>
@enderror
