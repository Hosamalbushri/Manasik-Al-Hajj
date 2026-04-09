@php
    $navItems = [
        ['label' => 'الرئيسية', 'icon' => 'fas fa-home', 'url' => route('web.home.index')],
        ['label' => 'المناسك', 'icon' => 'fas fa-hands-praying', 'url' => '#'],
        ['label' => 'المواعيد', 'icon' => 'fas fa-calendar-alt', 'url' => '#'],
        ['label' => 'الخرائط', 'icon' => 'fas fa-map-marked-alt', 'url' => '#'],
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
            <a href="{{ route('web.home.index') }}" class="logo">
                <i class="fas fa-kaaba logo-icon"></i>
                <div>
                    <span class="logo-text">مناسك الحج</span>
                    <span class="logo-sub">| حج مبرور</span>
                </div>
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

                <button class="login-btn" id="webLoginBtn" type="button">
                    <i class="fas fa-user-circle"></i><span>{{ __('web::app.header.login') }}</span>
                </button>

                <button class="burger" id="webBurgerBtn" type="button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="web-hajj-overlay" id="webOverlay"></div>
