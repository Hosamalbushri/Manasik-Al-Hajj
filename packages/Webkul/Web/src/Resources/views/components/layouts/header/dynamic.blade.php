@props(['opts' => []])

@php
    use Illuminate\Support\Facades\Storage;
    use Webkul\Web\Support\WebCoreStoreBranding;
    use Webkul\Web\Support\WebHeaderPrimaryTabs;

    $o = is_array($opts) ? $opts : [];
    $brand = array_merge([
        'icon' => 'fas fa-kaaba', 'title' => '', 'subtitle' => '', 'logo_path' => '',
    ], $o['brand'] ?? []);
    $homeUrl = \Illuminate\Support\Facades\Route::has('web.home.index') ? route('web.home.index') : '#';
    $webHomeUrlForTitle = $homeUrl !== '#' ? $homeUrl : url('/');
    $logoPath = trim((string) ($brand['logo_path'] ?? ''));
    $logoUrl = '';
    if ($logoPath !== '') {
        $pub = ltrim(str_replace('storage/', '', $logoPath), '/');
        if ($pub !== '' && Storage::disk('public')->exists($pub)) {
            $logoUrl = Storage::url($pub);
        }
    }
    if ($logoUrl === '') {
        $logoUrl = WebCoreStoreBranding::storefrontLogoUrl();
    }
    $dirAttr = in_array(strtolower(app()->getLocale()), ['ar', 'fa', 'he', 'ur', 'ku', 'dv'], true) ? 'rtl' : 'ltr';

    $navItems = [];
    $primary = is_array($o['nav_primary'] ?? null) ? $o['nav_primary'] : [];
    $hasPageKeys = isset($primary[0]['page_key']) && (string) $primary[0]['page_key'] !== '';
    $headerNavFromStructured = false;
    $primaryTabCount = count(WebHeaderPrimaryTabs::defaultKeyOrder());

    if ($hasPageKeys && count($primary) >= $primaryTabCount) {
        foreach ($primary as $row) {
            if (! is_array($row)) {
                continue;
            }
            $key = (string) ($row['page_key'] ?? '');
            if ($key === '') {
                continue;
            }
            $navItems[] = [
                'label' => (string) ($row['label'] ?? ''),
                'url'   => WebHeaderPrimaryTabs::resolveUrl($key),
            ];
        }
        $headerNavFromStructured = true;
    } elseif (
        is_array($o['nav_pages'] ?? null)
        && count($o['nav_pages']) >= $primaryTabCount
        && count($primary) >= $primaryTabCount
    ) {
        for ($i = 0; $i < $primaryTabCount; $i++) {
            $navItems[] = [
                'label' => (string) ($primary[$i]['label'] ?? ''),
                'url'   => (string) ($o['nav_pages'][$i]['url'] ?? ''),
            ];
        }
        $headerNavFromStructured = true;
    } elseif (count($primary) >= $primaryTabCount) {
        $order = WebHeaderPrimaryTabs::defaultKeyOrder();
        foreach (range(0, $primaryTabCount - 1) as $i) {
            $k = $order[$i] ?? null;
            if ($k === null) {
                break;
            }
            $navItems[] = [
                'label' => (string) ($primary[$i]['label'] ?? ''),
                'url'   => WebHeaderPrimaryTabs::resolveUrl($k),
            ];
        }
        $headerNavFromStructured = true;
    } elseif ($hasPageKeys) {
        foreach ($primary as $row) {
            if (! is_array($row)) {
                continue;
            }
            $key = (string) ($row['page_key'] ?? '');
            if ($key === '') {
                continue;
            }
            $navItems[] = [
                'label' => (string) ($row['label'] ?? ''),
                'url'   => WebHeaderPrimaryTabs::resolveUrl($key),
            ];
        }
        $headerNavFromStructured = true;
    } else {
        $navItems = is_array($o['nav'] ?? null) ? $o['nav'] : [];
    }

    if ($headerNavFromStructured) {
        foreach (is_array($o['nav_secondary'] ?? null) ? $o['nav_secondary'] : [] as $sec) {
            $sl = trim((string) ($sec['label'] ?? ''));
            $su = trim((string) ($sec['url'] ?? ''));
            if ($sl === '' && $su === '') {
                continue;
            }
            $navItems[] = ['label' => $sl, 'url' => $su];
        }
    }
    $lang = array_merge(['show_switcher' => true, 'button_label' => ''], $o['lang'] ?? []);
    $login = array_merge(['show' => true, 'label' => '', 'url' => ''], $o['login'] ?? []);
    $loginUrl = trim((string) ($login['url'] ?? ''));
    $loginLabel = trim((string) ($login['label'] ?? '')) ?: __('web::app.header.login');
    if (auth()->guard('hajj')->check()) {
        if (\Illuminate\Support\Facades\Route::has('hajj.account.index')) {
            $loginUrl = route('hajj.account.index');
        }
        $loginLabel = trans('web::hajj_account.nav');
    } elseif ($loginUrl === '' && \Illuminate\Support\Facades\Route::has('hajj.session.create')) {
        $loginUrl = route('hajj.session.create');
    }
    $langLabel = trim((string) ($lang['button_label'] ?? '')) ?: __('web::app.header.lang_button');
    $currentLocaleDisplay = strtoupper(app()->getLocale());
    $storeLocalesList = core()->storeLocales();
    $showWebLangSwitcher = ! empty($lang['show_switcher']) && count($storeLocalesList) > 1;

    $headerNavInlineStyle = '';
    if (is_array($o['colors'] ?? null)) {
        $headerThemeColors = array_merge(
            ['primary' => '#165022', 'secondary' => '#2E8B3A'],
            $o['colors']
        );
        $hp = strtoupper(trim((string) ($headerThemeColors['primary'] ?? '')));
        $hs = strtoupper(trim((string) ($headerThemeColors['secondary'] ?? '')));
        $headerNavStyleParts = [];
        if (preg_match('/^#[0-9A-F]{6}$/', $hp)) {
            $headerNavStyleParts[] = '--web-nav-primary: '.$hp;
            $headerNavStyleParts[] = '--web-nav-primary-strong: color-mix(in srgb, '.$hp.' 80%, black)';
        }
        if (preg_match('/^#[0-9A-F]{6}$/', $hs)) {
            $headerNavStyleParts[] = '--web-nav-accent: '.$hs;
        }
        $headerNavInlineStyle = $headerNavStyleParts !== [] ? implode('; ', $headerNavStyleParts).';' : '';
    }
@endphp



<nav class="web-hajj-navbar" dir="{{ $dirAttr }}" @if ($headerNavInlineStyle !== '') style="{{ $headerNavInlineStyle }}" @endif>
    <div class="web-hajj-navbar__backdrop" aria-hidden="true"></div>
    <div class="nav-container">
        <div class="nav-brand-zone">
            <a
                href="{{ $homeUrl }}"
                class="logo"
                aria-label="{{ __('web::app.components.layouts.header.desktop.bottom.logo-alt') }}"
                title="{{ $webHomeUrlForTitle }}"
            >
                @if ($logoUrl !== '')
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ __('web::app.layout.store_logo_alt', ['name' => $brand['title'] ?: config('app.name')]) }}"
                        class="header-brand-logo max-h-10 w-auto max-w-[200px] object-contain object-left"
                    >
                @else
                    <i class="{{ $brand['icon'] ?: 'fas fa-kaaba' }} logo-icon"></i>
                    <div>
                        <span class="logo-text">{{ $brand['title'] }}</span>
                        @if (! empty($brand['subtitle']))
                            <span class="logo-sub">{{ $brand['subtitle'] }}</span>
                        @endif
                    </div>
                @endif
            </a>
        </div>

        <div class="nav-links-zone">
            <ul class="nav-links" id="webNavLinks">
                <li class="web-nav-drawer__close-row" role="presentation">
                    <button
                        type="button"
                        class="web-nav-drawer__close"
                        id="webNavCloseBtn"
                        aria-label="{{ __('web::app.header.close_menu') }}"
                        title="{{ __('web::app.header.close_menu') }}"
                    >
                        <i class="fas fa-xmark" aria-hidden="true"></i>
                    </button>
                </li>
                @foreach ($navItems as $item)
                    @php
                        $u = (string) ($item['url'] ?? '#');
                        $isActive = $u !== '#' && rtrim(request()->url(), '/') === rtrim($u, '/');
                    @endphp
                    <li>
                        <a href="{{ $u ?: '#' }}" class="{{ $isActive ? 'is-active' : '' }}">
                            {{ $item['label'] ?? '' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="nav-actions-zone">
            <div class="nav-actions">
                @if ($showWebLangSwitcher)
                    <div class="lang-dropdown" id="webLangDropdown">
                        <button
                            class="lang-btn"
                            id="webLangBtn"
                            type="button"
                            aria-label="{{ $langLabel }} — {{ $currentLocaleDisplay }}"
                            title="{{ $langLabel }}"
                        >
                            <i class="fas fa-globe" aria-hidden="true"></i>
                            <span class="lang-btn-code">{{ $currentLocaleDisplay }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.7rem;" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu" id="webLangMenu">
                            @foreach ($storeLocalesList as $localeOption)
                                @php($code = $localeOption['value'])
                                @php($label = $localeOption['title'])
                                <a href="{{ route('web.locale.switch', ['locale_code' => $code]) }}">
                                    @if ($code === app()->getLocale())
                                        <i class="fas fa-check-circle" style="color:#2e7d32;"></i>
                                    @else
                                        <i class="fas fa-language"></i>
                                    @endif
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! empty($login['show']))
                    <button class="login-btn" id="webLoginBtn" type="button" @if($loginUrl !== '') data-login-url="{{ e($loginUrl) }}" @endif>
                        <i class="fas fa-user-circle"></i><span>{{ $loginLabel }}</span>
                    </button>
                @endif

                <button class="burger" id="webBurgerBtn" type="button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="web-hajj-overlay" id="webOverlay"></div>
