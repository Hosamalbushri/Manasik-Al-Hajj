<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.forget-password.create.page-title')
    </x-slot>

    <div class="admin-login-page-hajj">
        <div class="login-container">
            <div class="login-card">
                {!! view_render_event('admin.sessions.forgor_password.form_controls.before') !!}

                <div class="login-header">
                    <div class="login-logo">
                        @include('admin::sessions.partials.site-logo')
                    </div>
                </div>

                <p class="login-form-intro">
                    @lang('admin::app.users.forget-password.create.title')
                </p>

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

                <x-admin::form :action="route('admin.forgot_password.store')">
                    <div class="input-group">
                        <x-admin::form.control-group.label
                            class="input-label required"
                            for="email"
                        >
                            @lang('admin::app.users.forget-password.create.email')
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
                                :label="trans('admin::app.users.forget-password.create.email')"
                                :placeholder="trans('admin::app.users.forget-password.create.email')"
                                class="!shadow-none"
                            />
                        </div>

                        <x-admin::form.control-group.error
                            class="login-field-error-msg"
                            control-name="email"
                        />
                    </div>

                    <div class="login-footer-actions">
                        <a
                            class="forgot-link"
                            href="{{ route('admin.session.create') }}"
                        >
                            @lang('admin::app.users.forget-password.create.sign-in-link')
                        </a>

                        <button
                            type="submit"
                            class="login-btn"
                            aria-label="{{ trans('admin::app.users.forget-password.create.submit-btn') }}"
                        >
                            @lang('admin::app.users.forget-password.create.submit-btn')
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.forgor_password.form_controls.after') !!}
            </div>

            @include('admin::sessions.partials.footer-credits')
        </div>
    </div>
</x-admin::layouts.anonymous>
