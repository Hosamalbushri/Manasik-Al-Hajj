@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Webkul\Web\Support\WebCoreStoreBranding;

    $activeTab = isset($active) && $active === 'register' ? 'register' : 'login';
    $storefrontLogoUrl = WebCoreStoreBranding::storefrontLogoUrl();
    $storefrontFaviconPath = WebCoreStoreBranding::storefrontFaviconStoragePath();
    $webHomeUrl = Route::has('web.home.index') ? route('web.home.index') : url('/');
@endphp

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'fa'], true) ? 'rtl' : 'ltr' }}"
>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        @if ($storefrontFaviconPath !== '')
            <link
                rel="icon"
                href="{{ Storage::url($storefrontFaviconPath) }}"
                sizes="32x32"
            >
        @endif

        @stack('meta')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        {{
            vite()->set(
                ['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'],
                'web'
            )
        }}
    </head>
    <body
        class="hajj-auth-page"
        data-hajj-forgot-msg="{{ e(__('web::hajj_auth.auth-shell.forgot_soon')) }}"
    >
        <x-web::flash-group />

        <div class="bg-gradient" aria-hidden="true"></div>
        <div class="bg-glass" aria-hidden="true"></div>
        <div class="orb orb-1" aria-hidden="true"></div>
        <div class="orb orb-2" aria-hidden="true"></div>
        <div class="orb orb-3" aria-hidden="true"></div>

        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-welcome">
                    <div class="welcome-content">
                        @if ($storefrontLogoUrl !== '')
                            <a
                                href="{{ $webHomeUrl }}"
                                class="welcome-brand-link"
                                aria-label="{{ __('web::app.components.layouts.header.desktop.bottom.logo-alt') }}"
                                title="{{ $webHomeUrl }}"
                            >
                                <img
                                    src="{{ $storefrontLogoUrl }}"
                                    alt="{{ __('web::app.layout.store_logo_alt', ['name' => config('app.name')]) }}"
                                    class="welcome-brand-logo"
                                >
                            </a>
                            <h1 class="hajj-auth-sr-only">
                                {{ __('web::hajj_auth.auth-shell.welcome_lead') }}
                                {{ __('web::hajj_auth.auth-shell.welcome_brand') }}
                            </h1>
                        @else
                            <a
                                href="{{ $webHomeUrl }}"
                                class="welcome-brand-link"
                                aria-label="{{ __('web::app.components.layouts.header.desktop.bottom.logo-alt') }}"
                                title="{{ $webHomeUrl }}"
                            >
                                <div class="welcome-icon" aria-hidden="true">
                                    <i class="fas fa-kaaba"></i>
                                </div>
                                <h1 class="welcome-title">
                                    {{ __('web::hajj_auth.auth-shell.welcome_lead') }}
                                    <span>{{ __('web::hajj_auth.auth-shell.welcome_brand') }}</span>
                                </h1>
                            </a>
                        @endif
                        <div class="welcome-divider" aria-hidden="true"></div>
                        <p class="welcome-text">{{ __('web::hajj_auth.auth-shell.welcome_text') }}</p>
                    </div>
                </div>

                <div class="auth-form">
                    <div class="form-tabs" role="tablist">
                        <a
                            href="{{ route('hajj.session.create') }}"
                            role="tab"
                            @class(['form-tab', 'active' => $activeTab === 'login'])
                            aria-selected="{{ $activeTab === 'login' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_auth.auth-shell.tab_login') }}</a>
                        <a
                            href="{{ route('hajj.register.create') }}"
                            role="tab"
                            @class(['form-tab', 'active' => $activeTab === 'register'])
                            aria-selected="{{ $activeTab === 'register' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_auth.auth-shell.tab_register') }}</a>
                    </div>

                    @yield('auth-form')
                </div>
            </div>

            <p class="hajj-auth-page-footer">
                @hasSection('auth-footer')
                    @yield('auth-footer')
                @else
                    {{ __('web::hajj_auth.login-form.footer', ['current_year' => date('Y')]) }}
                @endif
            </p>
        </div>

        <x-web::modal-confirm />

        @stack('scripts')
    </body>
</html>
