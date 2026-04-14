@props([
    'hasHeader' => true,
    'hasFooter' => true,
    'title' => null,
])

@php
    use Webkul\Web\Support\WebCoreStoreBranding;
    use Webkul\Web\Support\WebStoreBandColors;

    $fb = config('web.identity.fallback_shop_colors', []);
    $fbPrimary = $fb['primary'] ?? '#165022';
    $fbAccent = $fb['accent'] ?? '#2E8B3A';
    $fbIcon = $fb['icon'] ?? '#A67C2A';
    $fbBadge = $fb['badge'] ?? '#D4AF37';

    $shopPrimary = core()->getConfigData('general.store.web.primary_color')
        ?: core()->getConfigData('general.store.shop.primary_color')
        ?: core()->getConfigData('general.design.shop.primary_color')
        ?: $fbPrimary;
    $shopAccent = core()->getConfigData('general.store.web.accent_color')
        ?: core()->getConfigData('general.store.shop.accent_color')
        ?: core()->getConfigData('general.design.shop.accent_color')
        ?: $fbAccent;
    $shopIconColor = core()->getConfigData('general.store.web.icon_color')
        ?: core()->getConfigData('general.store.shop.icon_color')
        ?: core()->getConfigData('general.design.shop.icon_color')
        ?: $fbIcon;
    $shopBadgeColor = core()->getConfigData('general.store.web.badge_color')
        ?: core()->getConfigData('general.store.shop.badge_color')
        ?: core()->getConfigData('general.design.shop.badge_color')
        ?: $fbBadge;
    if (! preg_match('/^#[0-9A-Fa-f]{6}$/', $shopPrimary)) {
        $shopPrimary = $fbPrimary;
    }
    if (! preg_match('/^#[0-9A-Fa-f]{6}$/', $shopAccent)) {
        $shopAccent = $fbAccent;
    }
    if (! preg_match('/^#[0-9A-Fa-f]{6}$/', $shopIconColor)) {
        $shopIconColor = $shopAccent;
    }
    if (! preg_match('/^#[0-9A-Fa-f]{6}$/', $shopBadgeColor)) {
        $shopBadgeColor = $shopPrimary;
    }

    $identityOv = config('web.identity', []);
    $hexOk = static fn ($v): bool => is_string($v) && $v !== '' && preg_match('/^#[0-9A-Fa-f]{3,8}$/', trim($v));
    $identityCss = '';
    $mapOv = [
        'card_ink' => '--web-card-ink',
        'card_muted' => '--web-card-muted',
        'accent_gold' => '--web-accent-gold',
        'accent_gold_bright' => '--web-accent-gold-bright',
        'parchment_start' => '--web-parchment-start',
        'parchment_mid' => '--web-parchment-mid',
        'parchment_end' => '--web-parchment-end',
    ];
    foreach ($mapOv as $cfgKey => $cssName) {
        $val = $identityOv[$cfgKey] ?? null;
        if ($hexOk($val)) {
            $identityCss .= $cssName.': '.trim((string) $val).';';
        }
    }

    $storeInnerBand = WebStoreBandColors::innerHeroStoreDefaults();
    $storeDividerBand = WebStoreBandColors::sectionDividerStoreDefaults();
    $storefrontFaviconPath = WebCoreStoreBranding::storefrontFaviconStoragePath();
@endphp

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}"
    style="
        --shop-font-sans: 'Cairo', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji';
        --shop-primary: {{ $shopPrimary }};
        --shop-accent: {{ $shopAccent }};
        --shop-icon-color: {{ $shopIconColor }};
        --shop-badge-color: {{ $shopBadgeColor }};
        --shop-primary-hover: color-mix(in srgb, var(--shop-primary) 88%, black);
        --shop-accent-hover: color-mix(in srgb, var(--shop-accent) 82%, black);
        --shop-ring: color-mix(in srgb, var(--shop-primary) 55%, transparent);
        --shop-surface: color-mix(in srgb, var(--shop-primary) 12%, white);
        --shop-surface-strong: color-mix(in srgb, var(--shop-primary) 22%, white);
        --shop-border-soft: color-mix(in srgb, var(--shop-primary) 28%, white);
        --shop-border-hover: color-mix(in srgb, var(--shop-primary) 38%, white);
        --shop-gradient-from: color-mix(in srgb, var(--shop-primary) 16%, white);
        --shop-gradient-mid: color-mix(in srgb, var(--shop-primary) 6%, white);
        --shop-placeholder: color-mix(in srgb, var(--shop-primary) 42%, #64748b);
        --shop-badge-ring: color-mix(in srgb, var(--shop-primary) 14%, transparent);
        --web-store-inner-hero-from: {{ $storeInnerBand['from'] }};
        --web-store-inner-hero-mid: {{ $storeInnerBand['mid'] }};
        --web-store-inner-hero-to: {{ $storeInnerBand['to'] }};
        --web-store-section-divider-from: {{ $storeDividerBand['from'] }};
        --web-store-section-divider-mid: {{ $storeDividerBand['mid'] }};
        --web-store-section-divider-to: {{ $storeDividerBand['to'] }};
        {{ $identityCss }}
    "
>
    <head>
        {!! view_render_event('shop.layout.head.before') !!}

        <title>{{ $title ?? __('web::app.meta.title') }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >

        @if ($storefrontFaviconPath !== '')
            <link
                type="image/x-icon"
                href="{{ \Illuminate\Support\Facades\Storage::url($storefrontFaviconPath) }}"
                rel="shortcut icon"
                sizes="16x16"
            >
        @else
            <link
                type="image/x-icon"
                href="{{ vite()->asset('images/favicon.ico') }}"
                rel="shortcut icon"
                sizes="16x16"
            >
        @endif

        @stack('meta')

        {{
            vite()->set(
                ['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'],
                'web'
            )
        }}

        {{-- Styles stack must run after slot + footer: @push from page/footer renders later than <head>. --}}

        {!! view_render_event('shop.layout.head.after') !!}
    </head>

    <body>
        {!! view_render_event('shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            {{ __('web::app.components.layouts.skip-to-content') }}
        </a>

        <x-web::flash-group />

        @if ($hasHeader)
            <x-web::layouts.header />
        @endif

        {!! view_render_event('shop.layout.content.before') !!}

        <main
            id="main"
            class="min-h-[40vh] bg-white"
        >
            {{ $slot }}
        </main>

        {!! view_render_event('shop.layout.content.after') !!}

        @if ($hasFooter)
            <x-web::layouts.footer />
        @endif

        @stack('styles')

        {!! view_render_event('shop.layout.body.after') !!}

        <x-web::modal-confirm />

        @stack('scripts')
    </body>
</html>
