@php
    $adminLogo = core()->getConfigData('general.design.admin_logo.logo_image');
@endphp
@if ($adminLogo)
    <img
        class="max-h-14 max-w-[240px] w-auto object-contain"
        src="{{ Storage::url($adminLogo) }}"
        alt="{{ config('app.name') }}"
    >
@else
    <img
        class="max-h-12 w-auto max-w-full object-contain"
        src="{{ vite()->asset('images/logo.svg') }}"
        alt="{{ config('app.name') }}"
    >
@endif
