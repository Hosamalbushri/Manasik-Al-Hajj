<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.login.title')
    </x-slot>

    <div class="admin-login-page-hajj">
        <a
            href="{{ url('/') }}"
            class="back-to-site"
        >
            @if (in_array(app()->getLocale(), ['fa', 'ar'], true))
                <span
                    class="icon-right-arrow"
                    aria-hidden="true"
                ></span>
            @else
                <span
                    class="icon-left-arrow"
                    aria-hidden="true"
                ></span>
            @endif

            <span>@lang('admin::app.users.login.back-to-site')</span>
        </a>

        <div class="login-container">
            <div class="login-card">
                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <div class="login-header">
                    <div class="login-logo">
                        @include('admin::sessions.partials.site-logo')
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert-message alert-error">
                        <span
                            class="icon-error"
                            aria-hidden="true"
                        ></span>

                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert-message alert-warning">
                        <span
                            class="icon-warning"
                            aria-hidden="true"
                        ></span>

                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                <x-admin::form :action="route('admin.session.store')">
                    <div class="input-group">
                        <x-admin::form.control-group.label
                            class="input-label required"
                            for="email"
                        >
                            @lang('admin::app.users.login.email')
                        </x-admin::form.control-group.label>

                        <div class="input-wrapper">
                            <span
                                class="icon-mail field-icon"
                                aria-hidden="true"
                            ></span>

                            <x-admin::form.control-group.control
                                type="email"
                                id="email"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('admin::app.users.login.email')"
                                :placeholder="trans('admin::app.users.login.email')"
                                class="!shadow-none"
                            />
                        </div>

                        <x-admin::form.control-group.error
                            class="login-field-error-msg"
                            control-name="email"
                        />
                    </div>

                    <div class="input-group">
                        <x-admin::form.control-group.label
                            class="input-label required"
                            for="password"
                        >
                            @lang('admin::app.users.login.password')
                        </x-admin::form.control-group.label>

                        <div class="input-wrapper">
                            <span
                                class="field-icon field-icon--svg"
                                aria-hidden="true"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect
                                        x="5"
                                        y="11"
                                        width="14"
                                        height="10"
                                        rx="2"
                                    />

                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>

                            <x-admin::form.control-group.control
                                type="password"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                :label="trans('admin::app.users.login.password')"
                                :placeholder="trans('admin::app.users.login.password')"
                                class="!shadow-none"
                            />

                            <button
                                type="button"
                                class="toggle-password"
                                aria-label="@lang('admin::app.users.login.password')"
                            >
                                <span
                                    class="icon-eye js-toggle-password-icon"
                                    aria-hidden="true"
                                ></span>
                            </button>
                        </div>

                        <x-admin::form.control-group.error
                            class="login-field-error-msg"
                            control-name="password"
                        />
                    </div>

                    <div class="login-options">
                        <label class="checkbox">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <span>@lang('admin::app.users.login.remember-me')</span>
                        </label>

                        <a
                            class="forgot-link"
                            href="{{ route('admin.forgot_password.create') }}"
                        >
                            @lang('admin::app.users.login.forget-password-link')
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="login-btn"
                        aria-label="{{ trans('admin::app.users.login.submit-btn') }}"
                    >
                        @lang('admin::app.users.login.submit-btn')
                    </button>
                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}
            </div>

            @include('admin::sessions.partials.footer-credits')
        </div>
    </div>
</x-admin::layouts.anonymous>
