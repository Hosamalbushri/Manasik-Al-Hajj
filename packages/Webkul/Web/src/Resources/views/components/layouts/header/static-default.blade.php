@php
    use Illuminate\Support\Facades\Route;
    use Webkul\Web\Support\WebCoreStoreBranding;

    $coreStoreLogoUrl = WebCoreStoreBranding::storefrontLogoUrl();
    $webHomeUrl = Route::has('web.home.index') ? route('web.home.index') : url('/');
    $hajjLoginUrl = '';
    $hajjLoginLabel = __('web::app.header.login');
    if (auth()->guard('hajj')->check() && \Illuminate\Support\Facades\Route::has('hajj.account.index')) {
        $hajjLoginUrl = route('hajj.account.index');
        $hajjLoginLabel = trans('web::hajj_account.nav');
    } elseif (\Illuminate\Support\Facades\Route::has('hajj.session.create')) {
        $hajjLoginUrl = route('hajj.session.create');
    }
    $navItems = [
        ['label' => 'الرئيسية', 'icon' => 'fas fa-home', 'url' => route('web.home.index')],
        ['label' => 'المناسك', 'icon' => 'fas fa-hands-praying', 'url' => \Illuminate\Support\Facades\Route::has('web.manasik.index') ? route('web.manasik.index') : '/manasik'],
        ['label' => 'الخرائط', 'icon' => 'fas fa-map-marked-alt', 'url' => \Illuminate\Support\Facades\Route::has('web.maps.index') ? route('web.maps.index') : '/maps'],
        ['label' => 'الأذكار والأدعية', 'icon' => 'fas fa-hands-praying', 'url' => \Illuminate\Support\Facades\Route::has('web.adhkar.index') ? route('web.adhkar.index') : '/adhkar'],
        ['label' => 'الدعم', 'icon' => 'fas fa-phone-alt', 'url' => '#'],
    ];
    $storeLocalesList = core()->storeLocales();
    $showWebLangSwitcher = count($storeLocalesList) > 1;
@endphp

@pushOnce('styles', 'web-hajj-navbar-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endPushOnce

<nav class="web-hajj-navbar" dir="rtl">
    <div class="web-hajj-navbar__backdrop" aria-hidden="true"></div>
    <div class="nav-container">
        <div class="nav-brand-zone">
            <a
                href="{{ $webHomeUrl }}"
                class="logo"
                aria-label="{{ __('web::app.components.layouts.header.desktop.bottom.logo-alt') }}"
                title="{{ $webHomeUrl }}"
            >
                @if ($coreStoreLogoUrl !== '')
                    <img
                        src="{{ $coreStoreLogoUrl }}"
                        alt="{{ __('web::app.layout.store_logo_alt', ['name' => config('app.name')]) }}"
                        class="header-brand-logo max-h-10 w-auto max-w-[200px] object-contain object-left"
                    >
                @else
                    <i class="fas fa-kaaba logo-icon"></i>
                    <div>
                        <span class="logo-text">مناسك الحج</span>
                        <span class="logo-sub">| حج مبرور</span>
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
                        $isActive = $item['url'] !== '#' && rtrim(request()->url(), '/') === rtrim($item['url'], '/');
                    @endphp
                    <li>
                        <a href="{{ $item['url'] }}" class="{{ $isActive ? 'is-active' : '' }}">
                            {{ $item['label'] }}
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
                            aria-label="{{ __('web::app.header.lang_button') }} — {{ strtoupper(app()->getLocale()) }}"
                            title="{{ __('web::app.header.lang_button') }}"
                        >
                            <i class="fas fa-globe" aria-hidden="true"></i>
                            <span class="lang-btn-code">{{ strtoupper(app()->getLocale()) }}</span>
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

                <button class="login-btn" id="webLoginBtn" type="button" @if($hajjLoginUrl !== '') data-login-url="{{ e($hajjLoginUrl) }}" @endif>
                    <i class="fas fa-user-circle"></i><span>{{ $hajjLoginLabel }}</span>
                </button>

                <button class="burger" id="webBurgerBtn" type="button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="web-hajj-overlay" id="webOverlay"></div>
