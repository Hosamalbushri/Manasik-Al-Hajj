{!! view_render_event('shop.layout.footer.before') !!}

@php
    use Illuminate\Support\Facades\Schema;
    use Webkul\Web\Repositories\WebThemeCustomizationRepository;

    $webFooter = null;
    if (Schema::hasTable('shop_theme_customizations')) {
        $webFooter = app(WebThemeCustomizationRepository::class)
            ->getActiveWebFooter(config('web.storefront_theme_code', 'web'));
    }
    $footerOpts = null;
    if ($webFooter && is_array($webFooter->options) && ($webFooter->options['enabled'] ?? true)) {
        $footerOpts = $webFooter->options;
    }
@endphp

@if ($footerOpts)
    <x-web::layouts.footer.dynamic :opts="$footerOpts" />
@else
    <x-web::layouts.footer.unique />
@endif

{!! view_render_event('shop.layout.footer.after') !!}
