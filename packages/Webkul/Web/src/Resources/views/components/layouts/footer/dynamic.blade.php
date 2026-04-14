@props(['opts' => []])

@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Webkul\Web\Support\WebCoreStoreBranding;

    $o = is_array($opts) ? $opts : [];
    $webHomeUrl = Route::has('web.home.index') ? route('web.home.index') : url('/');
    $ufId = 'webuf-' . \Illuminate\Support\Str::random(8);
    $isRtl = in_array(app()->getLocale(), ['ar', 'fa'], true);
    $chev = $isRtl ? 'fa-chevron-left' : 'fa-chevron-right';

    $visibility = array_merge([
        'brand' => true,
        'social' => true,
        'explore' => true,
        'support' => true,
        'contact' => true,
        'subscribe' => false,
        'bottom' => true,
        'bottom_mini' => true,
    ], is_array($o['visibility'] ?? null) ? $o['visibility'] : []);

    $visOn = static function (string $key) use ($visibility): bool {
        if (! array_key_exists($key, $visibility)) {
            return true;
        }

        return filter_var($visibility[$key], FILTER_VALIDATE_BOOLEAN);
    };

    $brand = array_merge([
        'icon' => 'fas fa-kaaba', 'title' => '', 'description' => '', 'trust' => '', 'logo_path' => '',
    ], $o['brand'] ?? []);

    $logoPath = trim((string) ($brand['logo_path'] ?? ''));
    $brandLogoUrl = '';
    if ($logoPath !== '') {
        $pub = ltrim(str_replace('storage/', '', $logoPath), '/');
        if ($pub !== '' && Storage::disk('public')->exists($pub)) {
            $brandLogoUrl = Storage::url($pub);
        }
    }
    if ($brandLogoUrl === '') {
        $brandLogoUrl = WebCoreStoreBranding::storefrontLogoUrl();
    }

    $social = is_array($o['social'] ?? null) ? $o['social'] : [];
    $colExplore = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_explore'] ?? []);
    $colSupport = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_support'] ?? []);
    $contact = array_merge(['title' => '', 'items' => []], $o['contact'] ?? []);
    $subscribe = array_merge([
        'title' => '', 'placeholder' => '', 'privacy' => '',
        'success_msg' => '', 'invalid_msg' => '',
    ], $o['subscribe'] ?? []);
    $bottom = array_merge(['copyright' => '', 'mini_nav_label' => '', 'links' => []], $o['bottom'] ?? []);
    $effects = array_merge(['back_to_top' => true], $o['effects'] ?? []);

    $copyrightRaw = trim((string) ($bottom['copyright'] ?? ''));
    $copyrightLine = $copyrightRaw !== ''
        ? str_replace(':year', (string) date('Y'), $copyrightRaw)
        : __('web::app.manasik_footer.copyright', ['year' => date('Y')]);

    $okMsg = trim((string) ($subscribe['success_msg'] ?? '')) ?: __('web::app.manasik_footer.nl_success');
    $badMsg = trim((string) ($subscribe['invalid_msg'] ?? '')) ?: __('web::app.manasik_footer.nl_invalid');
    $miniNavAria = trim((string) ($bottom['mini_nav_label'] ?? '')) ?: __('web::app.manasik_footer.mini_nav');

    $showBrandBlock = $visOn('brand');
    $showSocialBlock = $visOn('social');
    $showColBrand = $showBrandBlock || ($showSocialBlock && $social !== []);
    $showExplore = $visOn('explore');
    $showSupport = $visOn('support');
    $showContact = $visOn('contact');
    $showSubscribe = $visOn('subscribe');
    $showColAside = $showContact || $showSubscribe;
    $mainColCount = (int) $showColBrand + (int) $showExplore + (int) $showSupport + (int) $showColAside;
    $gridTemplate = match ($mainColCount) {
        0 => 'none',
        1 => 'minmax(0, 1fr)',
        2 => 'minmax(0, 1fr) minmax(0, 1fr)',
        3 => 'minmax(0, 1.15fr) minmax(0, 1fr) minmax(0, 1fr)',
        default => 'minmax(0, 1.35fr) minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1.25fr)',
    };
    $showBottomBar = $visOn('bottom');
    $showBottomMini = $visOn('bottom_mini');

    $webUfInlineStyle = '';
    if (is_array($o['colors'] ?? null)) {
        $footerThemeColors = array_merge(
            ['primary' => '#D4AF37', 'secondary' => '#0D2A1A'],
            $o['colors']
        );
        $footerStyleParts = [];
        $fp = strtoupper(trim((string) ($footerThemeColors['primary'] ?? '')));
        $fs = strtoupper(trim((string) ($footerThemeColors['secondary'] ?? '')));
        if (preg_match('/^#[0-9A-F]{6}$/', $fp)) {
            $footerStyleParts[] = '--web-uf-primary: '.$fp;
        }
        if (preg_match('/^#[0-9A-F]{6}$/', $fs)) {
            $footerStyleParts[] = '--web-uf-secondary: '.$fs;
        }
        $webUfInlineStyle = $footerStyleParts !== [] ? implode('; ', $footerStyleParts).';' : '';
    }
@endphp

@pushOnce('styles', 'web-footer-fontawesome')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
@endPushOnce

<footer
    class="web-uf"
    id="{{ $ufId }}"
    role="contentinfo"
    data-web-uf-footer="1"
    @if ($webUfInlineStyle !== '') style="{{ $webUfInlineStyle }}" @endif
>
    <div class="web-uf__bg">
        <div class="web-uf__container">
            @if ($mainColCount > 0)
                <div class="web-uf__grid" style="--web-uf-grid-cols: {{ $gridTemplate }};">
                    @if ($showColBrand)
                        <div class="web-uf__brand">
                            @if ($showBrandBlock)
                                <div class="web-uf__logo-pill">
                                    <a
                                        href="{{ $webHomeUrl }}"
                                        class="web-uf__brand-mark"
                                        aria-label="{{ __('web::app.components.layouts.header.desktop.bottom.logo-alt') }}"
                                        title="{{ $webHomeUrl }}"
                                    >
                                        @if ($brandLogoUrl !== '')
                                            <img
                                                src="{{ $brandLogoUrl }}"
                                                alt="{{ __('web::app.layout.store_logo_alt', ['name' => $brand['title'] ?: config('app.name')]) }}"
                                                class="web-uf__brand-logo"
                                            >
                                        @else
                                            <i class="{{ $brand['icon'] ?: 'fas fa-kaaba' }} web-uf__brand-icon" aria-hidden="true"></i>
                                            <div class="web-uf__brand-titles">
                                                <span class="web-uf__brand-title">{{ $brand['title'] ?: __('web::app.manasik_footer.brand') }}</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                @if (! empty($brand['description']))
                                    <p class="web-uf__desc">{{ $brand['description'] }}</p>
                                @endif
                                @if (! empty($brand['trust']))
                                    <div class="web-uf__trust">
                                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                                        <span>{{ $brand['trust'] }}</span>
                                    </div>
                                @endif
                            @endif
                            @if ($showSocialBlock && $social !== [])
                                <div class="web-uf__social">
                                    @foreach ($social as $s)
                                        @php
                                            $sUrl = (string) ($s['url'] ?? '#');
                                            $sIcon = (string) ($s['icon'] ?? 'fab fa-link');
                                            $sAria = trim((string) ($s['aria_label'] ?? ''));
                                        @endphp
                                        <a href="{{ $sUrl ?: '#' }}" @if($sAria !== '') aria-label="{{ $sAria }}" @endif>
                                            <i class="{{ $sIcon }}" aria-hidden="true"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($showExplore)
                        <div>
                            @if (! empty($colExplore['title']))
                                <h3 class="web-uf__col-title">{{ $colExplore['title'] }}</h3>
                            @endif
                            <ul class="web-uf__links">
                                @foreach ($colExplore['links'] ?? [] as $row)
                                    <li>
                                        <a href="{{ $row['url'] ?? '#' }}">
                                            @if (! empty($colExplore['show_chevron']))
                                                <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $row['label'] ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($showSupport)
                        <div>
                            @if (! empty($colSupport['title']))
                                <h3 class="web-uf__col-title">{{ $colSupport['title'] }}</h3>
                            @endif
                            <ul class="web-uf__links">
                                @foreach ($colSupport['links'] ?? [] as $row)
                                    <li>
                                        <a href="{{ $row['url'] ?? '#' }}">
                                            @if (! empty($colSupport['show_chevron']))
                                                <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $row['label'] ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($showColAside)
                        <div>
                            @if ($showContact)
                                @if (! empty($contact['title']))
                                    <h3 class="web-uf__col-title">{{ $contact['title'] }}</h3>
                                @endif
                                <div class="web-uf__contact">
                                    @foreach ($contact['items'] ?? [] as $it)
                                        <div class="web-uf__contact-item">
                                            @if (! empty($it['icon']))
                                                <i class="{{ $it['icon'] }}" aria-hidden="true"></i>
                                            @endif
                                            <span>{{ $it['text'] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($showSubscribe)
                                <div class="web-uf__subscribe-block">
                                    @if (! empty($subscribe['title']))
                                        <h3 class="web-uf__col-title web-uf__col-title--sub">{{ $subscribe['title'] }}</h3>
                                    @endif
                                    <div class="web-uf__nl">
                                        <input
                                            type="email"
                                            id="{{ $ufId }}-email"
                                            class="web-uf__nl-input"
                                            placeholder="{{ $subscribe['placeholder'] ?: __('web::app.manasik_footer.nl_placeholder') }}"
                                            autocomplete="email"
                                        >
                                        <button
                                            type="button"
                                            class="web-uf__nl-btn web-uf-nl-trigger"
                                            data-msg-ok="{{ e($okMsg) }}"
                                            data-msg-bad="{{ e($badMsg) }}"
                                            aria-label="{{ $subscribe['title'] ?: __('web::app.manasik_footer.titles.subscribe') }}"
                                        >
                                            <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    @if (! empty($subscribe['privacy']))
                                        <p class="web-uf__nl-note">
                                            <i class="fas fa-lock" aria-hidden="true"></i>
                                            {{ $subscribe['privacy'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if ($showBottomBar)
                <div class="web-uf__bar {{ ! $showBottomMini ? 'web-uf__bar--copyright-only' : '' }}">
                    <div class="web-uf__copyright">
                        <i class="far fa-copyright" aria-hidden="true"></i>
                        {{ $copyrightLine }}
                    </div>
                    @if ($showBottomMini)
                        <nav class="web-uf__mini" aria-label="{{ e($miniNavAria) }}">
                            @foreach ($bottom['links'] ?? [] as $m)
                                <a href="{{ $m['url'] ?? '#' }}">{{ $m['label'] ?? '' }}</a>
                            @endforeach
                        </nav>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if (! empty($effects['back_to_top']))
        <button
            type="button"
            class="web-uf__gotop"
            data-web-uf-gotop
            aria-label="{{ __('web::app.manasik_footer.back_top') }}"
        >
            <i class="fas fa-arrow-up" aria-hidden="true"></i>
        </button>
    @endif
</footer>
