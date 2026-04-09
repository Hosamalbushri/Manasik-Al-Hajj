@php
    use Illuminate\Support\Facades\Schema;
    use Webkul\Web\Repositories\WebThemeCustomizationRepository;

    $webHeader = null;
    if (Schema::hasTable('shop_theme_customizations')) {
        $webHeader = app(WebThemeCustomizationRepository::class)
            ->getActiveWebHeader(config('web.storefront_theme_code', 'web'));
    }
    $headerOpts = null;
    if ($webHeader && is_array($webHeader->options)) {
        $headerOpts = $webHeader->options;
    }
@endphp

{!! view_render_event('web.layout.header.before') !!}

@if ($headerOpts)
    <x-web::layouts.header.dynamic :opts="$headerOpts" />
@else
    @include('web::components.layouts.header.static-default')
@endif

{!! view_render_event('web.layout.header.after') !!}
