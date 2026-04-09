@props(['opts' => []])

@php
    $o = is_array($opts) ? $opts : [];
    $brand = array_merge([
        'icon' => 'fas fa-kaaba', 'title' => '', 'subtitle' => '', 'home_url' => '',
    ], $o['brand'] ?? []);
    $homeUrl = trim((string) ($brand['home_url'] ?? ''));
    if ($homeUrl === '') {
        $homeUrl = \Illuminate\Support\Facades\Route::has('web.home.index') ? route('web.home.index') : '#';
    }
    $dirAttr = (string) ($o['dir'] ?? 'auto');
    if (! in_array($dirAttr, ['rtl', 'ltr'], true)) {
        $dirAttr = in_array(app()->getLocale(), ['ar', 'fa'], true) ? 'rtl' : 'ltr';
    }
    $navItems = is_array($o['nav'] ?? null) ? $o['nav'] : [];
    $lang = array_merge(['show_switcher' => true, 'button_label' => ''], $o['lang'] ?? []);
    $login = array_merge(['show' => true, 'label' => '', 'url' => ''], $o['login'] ?? []);
    $loginUrl = trim((string) ($login['url'] ?? ''));
    $loginLabel = trim((string) ($login['label'] ?? '')) ?: __('web::app.header.login');
    $langLabel = trim((string) ($lang['button_label'] ?? '')) ?: __('web::app.header.lang_button');
    $currentLocaleDisplay = strtoupper(app()->getLocale());
    $storeLocalesList = core()->storeLocales();
    $showWebLangSwitcher = ! empty($lang['show_switcher']) && count($storeLocalesList) > 1;
@endphp

@pushOnce('styles', 'web-hajj-navbar-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endPushOnce

<nav class="web-hajj-navbar" dir="{{ $dirAttr }}">
    <div class="nav-container">
        <div class="nav-brand-zone">
            <a href="{{ $homeUrl }}" class="logo">
                <i class="{{ $brand['icon'] ?: 'fas fa-kaaba' }} logo-icon"></i>
                <div>
                    <span class="logo-text">{{ $brand['title'] }}</span>
                    @if (! empty($brand['subtitle']))
                        <span class="logo-sub">{{ $brand['subtitle'] }}</span>
                    @endif
                </div>
            </a>
        </div>

        <div class="nav-links-zone">
            <ul class="nav-links" id="webNavLinks">
                @foreach ($navItems as $item)
                    @php
                        $u = (string) ($item['url'] ?? '#');
                        $isActive = $u !== '#' && rtrim(request()->url(), '/') === rtrim($u, '/');
                    @endphp
                    <li>
                        <a href="{{ $u ?: '#' }}" class="{{ $isActive ? 'is-active' : '' }}">
                            @if (! empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @endif
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
