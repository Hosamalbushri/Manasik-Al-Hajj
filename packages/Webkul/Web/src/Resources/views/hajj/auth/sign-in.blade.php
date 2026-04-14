@extends('web::hajj.auth.layout')

@push('meta')
    <meta name="description" content="{{ __('web::hajj_auth.login-form.page-title') }}">
    <meta name="keywords" content="{{ __('web::hajj_auth.login-form.page-title') }}">
@endpush

@section('title', __('web::hajj_auth.login-form.page-title'))

@section('auth-form')
    <h2 class="hajj-auth-form-title">{{ __('web::hajj_auth.login-form.page-title') }}</h2>
    <p class="hajj-auth-form-sub">{{ __('web::hajj_auth.login-form.form-login-text') }}</p>

    <x-web::form :action="route('hajj.session.store')">
        @if (! empty($loginRedirect))
            <input type="hidden" name="redirect" value="{{ $loginRedirect }}">
        @endif
        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.login-form.email') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                type="email"
                name="email"
                id="hajj-login-email"
                :value="old('email')"
                :placeholder="trans('web::hajj_auth.login-form.email')"
                :aria-label="trans('web::hajj_auth.login-form.email')"
                aria-required="true"
                autocomplete="email"
            />

            <x-web::form.control-group.error control-name="email" class="field-error" />
        </x-web::form.control-group>

        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.login-form.password') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                hajj-password-toggle
                type="password"
                name="password"
                id="hajj-login-password"
                :placeholder="trans('web::hajj_auth.login-form.password')"
                :aria-label="trans('web::hajj_auth.login-form.password')"
                aria-required="true"
                autocomplete="current-password"
            />

            <x-web::form.control-group.error control-name="password" class="field-error" />
        </x-web::form.control-group>

        <div class="form-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                {{ __('web::hajj_auth.login-form.remember_me') }}
            </label>
            <button type="button" class="forgot-link" data-hajj-forgot>
                {{ __('web::hajj_auth.login-form.forgot-pass') }}
            </button>
        </div>

        <button type="submit" class="submit-btn">{{ __('web::hajj_auth.login-form.button-title') }}</button>
    </x-web::form>

    <p class="hajj-auth-outro">
        {{ __('web::hajj_auth.login-form.new-customer') }}
        <a href="{{ route('hajj.register.create') }}">{{ __('web::hajj_auth.login-form.create-your-account') }}</a>
    </p>
@endsection
