@php
    use Webkul\Web\Support\HajjAuthRegisterSettings;

    $gdprOn = HajjAuthRegisterSettings::gdprAgreementActive();
    $gdprLabel = HajjAuthRegisterSettings::gdprAgreementLabel();
    $gdprContent = HajjAuthRegisterSettings::gdprAgreementContent();
@endphp

@extends('web::hajj.auth.layout')

@push('meta')
    <meta name="description" content="{{ e(HajjAuthRegisterSettings::metaDescription()) }}">
    <meta name="keywords" content="{{ e(HajjAuthRegisterSettings::metaKeywords()) }}">
@endpush

@section('title', __('web::hajj_auth.signup-form.page-title'))

@section('auth-footer')
    {{ __('web::hajj_auth.signup-form.footer', ['current_year' => date('Y')]) }}
@endsection

@section('auth-form')
    <h2 class="hajj-auth-form-title">{{ __('web::hajj_auth.signup-form.page-title') }}</h2>
    <p class="hajj-auth-form-sub">{{ __('web::hajj_auth.signup-form.form-signup-text') }}</p>

    @if ($errors->any())
        <div class="hajj-auth-msg hajj-auth-msg--error" role="alert">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <span>{{ __('web::hajj_auth.signup-form.fix-errors-summary') }}</span>
        </div>
    @endif

    <x-web::form :action="route('hajj.register.store')">
        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.signup-form.full-name') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                type="text"
                name="name"
                :value="old('name')"
                :placeholder="trans('web::hajj_auth.signup-form.full-name')"
                :aria-label="trans('web::hajj_auth.signup-form.full-name')"
                aria-required="true"
                autocomplete="name"
            />

            <x-web::form.control-group.error control-name="name" class="field-error" />
        </x-web::form.control-group>

        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.signup-form.email') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                type="email"
                name="email"
                :value="old('email')"
                :placeholder="trans('web::hajj_auth.signup-form.email')"
                :aria-label="trans('web::hajj_auth.signup-form.email')"
                aria-required="true"
                autocomplete="email"
            />

            <x-web::form.control-group.error control-name="email" class="field-error" />
        </x-web::form.control-group>

        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.signup-form.phone') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                type="tel"
                name="phone"
                :value="old('phone')"
                :placeholder="trans('web::hajj_auth.signup-form.phone')"
                :aria-label="trans('web::hajj_auth.signup-form.phone')"
                autocomplete="tel"
            />

            <x-web::form.control-group.error control-name="phone" class="field-error" />
        </x-web::form.control-group>

        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.signup-form.password') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                hajj-password-toggle
                type="password"
                name="password"
                id="hajj-reg-password"
                :value="old('password')"
                :placeholder="trans('web::hajj_auth.signup-form.password')"
                :aria-label="trans('web::hajj_auth.signup-form.password')"
                aria-required="true"
                autocomplete="new-password"
            />

            <x-web::form.control-group.error control-name="password" class="field-error" />
        </x-web::form.control-group>

        <x-web::form.control-group class="input-group">
            <x-web::form.control-group.label class="hajj-auth-sr-only">
                {{ __('web::hajj_auth.signup-form.confirm-pass') }}
            </x-web::form.control-group.label>

            <x-web::form.control-group.control
                hajj-skin
                hajj-password-toggle
                type="password"
                name="password_confirmation"
                id="hajj-reg-password-confirmation"
                value=""
                :placeholder="trans('web::hajj_auth.signup-form.confirm-pass')"
                :aria-label="trans('web::hajj_auth.signup-form.confirm-pass')"
                aria-required="true"
                autocomplete="new-password"
            />

            <x-web::form.control-group.error control-name="password_confirmation" class="field-error" />
        </x-web::form.control-group>

        @if (HajjAuthRegisterSettings::newsletterSubscriptionEnabled())
            <div class="form-row hajj-auth-form-row--terms">
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="is_subscribed"
                        id="hajj-is-subscribed"
                        value="1"
                        @checked(old('is_subscribed'))
                    >
                    {{ __('web::hajj_auth.signup-form.subscribe-to-newsletter') }}
                </label>
            </div>
        @endif

        @if ($gdprOn)
            <div class="form-row hajj-auth-form-row--terms flex-wrap items-center gap-x-3 gap-y-2">
                <label class="checkbox-label" for="hajj-gdpr-agreement">
                    <input
                        type="checkbox"
                        name="agreement"
                        id="hajj-gdpr-agreement"
                        value="1"
                        @checked(old('agreement'))
                    >
                    <span>{!! $gdprLabel !!}</span>
                </label>
                @if (filled($gdprContent))
                    <button
                        type="button"
                        class="forgot-link"
                        onclick="document.getElementById('hajj-gdpr-terms-dialog')?.showModal()"
                    >
                        {{ __('web::hajj_auth.signup-form.click-here') }}
                    </button>
                @endif
            </div>
            <x-web::form.control-group.error control-name="agreement" class="field-error" />
        @else
            <div class="form-row hajj-auth-form-row--terms">
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="terms"
                        id="hajj-terms"
                        value="1"
                        @checked(old('terms'))
                    >
                    {{ __('web::hajj_auth.signup-form.terms-label') }}
                </label>
            </div>
            <x-web::form.control-group.error control-name="terms" class="field-error" />
        @endif

        <button type="submit" class="submit-btn">{{ __('web::hajj_auth.signup-form.button-title') }}</button>
    </x-web::form>

    @if ($gdprOn && filled($gdprContent))
        <dialog
            id="hajj-gdpr-terms-dialog"
            class="hajj-gdpr-dialog"
        >
            <div class="hajj-gdpr-dialog__header">
                <p>{{ __('web::hajj_auth.signup-form.terms-conditions') }}</p>
            </div>
            <div class="hajj-gdpr-dialog__body">
                {!! $gdprContent !!}
            </div>
            <div class="hajj-gdpr-dialog__footer">
                <form method="dialog">
                    <button type="submit" class="submit-btn">{{ __('web::hajj_auth.signup-form.close-dialog') }}</button>
                </form>
            </div>
        </dialog>
    @endif
@endsection
