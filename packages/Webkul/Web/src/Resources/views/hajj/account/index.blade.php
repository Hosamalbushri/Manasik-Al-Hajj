@php
    use Illuminate\Support\Carbon;

    $allowedTabs = ['info', 'security', 'preferences', 'favorites'];
    $tabParam = request('tab');
    $activeTab = in_array($tabParam, $allowedTabs, true) ? $tabParam : session('open_tab', 'info');
    if (! in_array($activeTab, $allowedTabs, true)) {
        $activeTab = 'info';
    }
    if ($errors->hasAny(['name', 'email', 'phone', 'birth_date', 'address'])) {
        $activeTab = 'info';
    }
    if ($errors->hasAny(['current_password', 'password', 'password_confirmation', 'delete_password'])) {
        $activeTab = 'security';
    }
    if ($errors->hasAny(['locale'])) {
        $activeTab = 'preferences';
    }

    $joinDate = $hajjUser->created_at
        ? Carbon::parse($hajjUser->created_at)->locale(app()->getLocale())->isoFormat('LL')
        : '—';

    $currentLocaleCode = strtolower((string) old('locale', $hajjUser->locale ?? app()->getLocale()));
    $localeRow = collect($storeLocaleOptions)->first(function ($o) use ($currentLocaleCode) {
        return strtolower((string) ($o['value'] ?? '')) === $currentLocaleCode;
    });
    $localeDisplayName = is_array($localeRow) ? (string) ($localeRow['title'] ?? $currentLocaleCode) : $currentLocaleCode;

    $hajjAccountHeroOverrides = [
        'title' => __('web::hajj_account.page_title'),
        'description' => __('web::hajj_account.page_subtitle_full'),
        'badge_show' => false,
        'primary_show' => false,
        'secondary_show' => false,
        'wave_fill' => '#ffffff',
        'breadcrumb' => [
            ['label' => __('web::app.inner_hero.nav_fallback_labels.home'), 'url' => route('web.home.index')],
            ['label' => __('web::hajj_account.page_title'), 'url' => ''],
        ],
    ];
@endphp

@push('meta')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
@endpush

<x-web::layouts :title="__('web::hajj_account.meta_title')" :hasHeader="true" :hasFooter="true">
    <div
        class="hajj-account-page hajj-account-page--v2"
        data-hajj-avatar-msg="{{ e(__('web::hajj_account.avatar_soon')) }}"
        data-hajj-delete-confirm="{{ e(__('web::hajj_account.delete.confirm')) }}"
        data-hajj-delete-final="{{ e(__('web::hajj_account.delete.final_confirm')) }}"
        data-fav-remove-confirm="{{ e(__('web::hajj_account.favorites.remove_confirm')) }}"
        data-fav-clear-confirm="{{ e(__('web::hajj_account.favorites.clear_confirm')) }}"
        @if ($errors->any() && ! session('success'))
            data-hajj-validation-summary="{{ e(__('web::hajj_account.errors_summary')) }}"
        @endif
    >
        <x-web::layouts.inner-page-hero :overrides="$hajjAccountHeroOverrides" />

        <div class="hajj-acc2-container">
            <div class="hajj-acc2-profile-grid">
                <aside class="hajj-acc2-sidebar">
                    <div class="hajj-acc2-avatar-wrap">
                        <div class="hajj-acc2-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </div>
                        <button type="button" class="hajj-acc2-avatar-edit" data-hajj-account-avatar aria-label="{{ __('web::hajj_account.avatar_soon') }}">
                            <i class="fas fa-camera" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div>
                        <h2 class="hajj-acc2-name">{{ $hajjUser->name }}</h2>
                        <p class="hajj-acc2-email">{{ $hajjUser->email }}</p>
                        <span class="hajj-acc2-badge">
                            @if ($hajjUser->email_verified_at)
                                <i class="fas fa-check-circle" aria-hidden="true"></i> {{ __('web::hajj_account.badge_verified') }}
                            @else
                                <i class="fas fa-user-check" aria-hidden="true"></i> {{ __('web::hajj_account.badge_active') }}
                            @endif
                        </span>
                    </div>
                    <dl class="hajj-acc2-stats">
                        <div class="hajj-acc2-stat">
                            <dt>{{ __('web::hajj_account.sidebar_joined') }}</dt>
                            <dd>{{ $joinDate }}</dd>
                        </div>
                        <div class="hajj-acc2-stat">
                            <dt>{{ __('web::hajj_account.sidebar_rituals_stat') }}</dt>
                            <dd>{{ (int) ($hajjUser->manasik_guide_completions_count ?? 0) }}</dd>
                        </div>
                        <div class="hajj-acc2-stat">
                            <dt>{{ __('web::hajj_account.sidebar_favorites_count') }}</dt>
                            <dd>{{ count($favoriteDuas) }}</dd>
                        </div>
                        <div class="hajj-acc2-stat">
                            <dt>{{ __('web::hajj_account.sidebar_preferred_lang') }}</dt>
                            <dd>{{ $localeDisplayName }}</dd>
                        </div>
                    </dl>
                </aside>

                <div class="hajj-acc2-main">
                    <div class="hajj-acc2-tabs" role="tablist">
                        <button
                            type="button"
                            class="hajj-account-tab-btn {{ $activeTab === 'info' ? 'is-active' : '' }}"
                            data-hajj-tab="info"
                            role="tab"
                            aria-selected="{{ $activeTab === 'info' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_account.tab_info') }}</button>
                        <button
                            type="button"
                            class="hajj-account-tab-btn {{ $activeTab === 'security' ? 'is-active' : '' }}"
                            data-hajj-tab="security"
                            role="tab"
                            aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_account.tab_security') }}</button>
                        <button
                            type="button"
                            class="hajj-account-tab-btn {{ $activeTab === 'preferences' ? 'is-active' : '' }}"
                            data-hajj-tab="preferences"
                            role="tab"
                            aria-selected="{{ $activeTab === 'preferences' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_account.tab_preferences') }}</button>
                        <button
                            type="button"
                            class="hajj-account-tab-btn {{ $activeTab === 'favorites' ? 'is-active' : '' }}"
                            data-hajj-tab="favorites"
                            role="tab"
                            aria-selected="{{ $activeTab === 'favorites' ? 'true' : 'false' }}"
                        >{{ __('web::hajj_account.tab_favorites') }}</button>
                    </div>

                    <div
                        class="hajj-acc2-panel {{ $activeTab === 'info' ? 'is-active' : '' }}"
                        data-hajj-tab-panel="info"
                        role="tabpanel"
                        @if ($activeTab !== 'info') hidden @endif
                    >
                        <form method="post" action="{{ route('hajj.account.profile.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="hajj-acc2-form-row">
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-name"><i class="fas fa-user" aria-hidden="true"></i>{{ __('web::hajj_account.form.full_name') }}</label>
                                    <input id="hajj-acc-name" name="name" type="text" class="hajj-acc2-input" value="{{ old('name', $hajjUser->name) }}" required autocomplete="name">
                                    @error('name')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-email"><i class="fas fa-envelope" aria-hidden="true"></i>{{ __('web::hajj_account.form.email') }}</label>
                                    <input id="hajj-acc-email" name="email" type="email" class="hajj-acc2-input" value="{{ old('email', $hajjUser->email) }}" required autocomplete="email">
                                    @error('email')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="hajj-acc2-form-row">
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-phone"><i class="fas fa-phone" aria-hidden="true"></i>{{ __('web::hajj_account.form.phone') }}</label>
                                    <input id="hajj-acc-phone" name="phone" type="tel" class="hajj-acc2-input" value="{{ old('phone', $hajjUser->phone) }}" dir="ltr" autocomplete="tel">
                                    @error('phone')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-birth"><i class="fas fa-calendar" aria-hidden="true"></i>{{ __('web::hajj_account.form.birthdate') }}</label>
                                    <input id="hajj-acc-birth" name="birth_date" type="date" class="hajj-acc2-input" value="{{ old('birth_date', optional($hajjUser->birth_date)->format('Y-m-d')) }}">
                                    @error('birth_date')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="hajj-acc2-form-group">
                                <label class="hajj-acc2-label" for="hajj-acc-address"><i class="fas fa-map-marker-alt" aria-hidden="true"></i>{{ __('web::hajj_account.form.address') }}</label>
                                <input id="hajj-acc-address" name="address" type="text" class="hajj-acc2-input" value="{{ old('address', $hajjUser->address) }}" autocomplete="street-address">
                                @error('address')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="hajj-acc2-actions">
                                <button type="submit" class="hajj-acc2-btn-primary"><i class="fas fa-save" aria-hidden="true"></i>{{ __('web::hajj_account.form.save') }}</button>
                                <a href="{{ route('hajj.account.index', ['tab' => 'info']) }}" class="hajj-acc2-btn-secondary">{{ __('web::hajj_account.form.cancel') }}</a>
                            </div>
                        </form>

                        <form method="post" action="{{ route('hajj.session.destroy') }}" class="hajj-acc2-logout-form">
                            @csrf
                            <button type="submit" class="hajj-acc2-btn-logout">
                                {{ __('web::hajj_account.logout') }}
                            </button>
                        </form>
                    </div>

                    <div
                        class="hajj-acc2-panel {{ $activeTab === 'security' ? 'is-active' : '' }}"
                        data-hajj-tab-panel="security"
                        role="tabpanel"
                        @if ($activeTab !== 'security') hidden @endif
                    >
                        <form method="post" action="{{ route('hajj.account.password.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="hajj-acc2-form-group">
                                <label class="hajj-acc2-label" for="hajj-acc-cur-pw"><i class="fas fa-lock" aria-hidden="true"></i>{{ __('web::hajj_account.password.current') }}</label>
                                <div class="hajj-acc2-pw-wrap">
                                    <input id="hajj-acc-cur-pw" name="current_password" type="password" class="hajj-acc2-input" placeholder="{{ __('web::hajj_account.password.placeholder_current') }}" autocomplete="current-password">
                                    <button type="button" class="hajj-acc2-pw-toggle" data-toggle-pass="hajj-acc-cur-pw" aria-label="{{ __('web::hajj_auth.login-form.show-password') }}"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                </div>
                                @error('current_password')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="hajj-acc2-form-row">
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-new-pw"><i class="fas fa-key" aria-hidden="true"></i>{{ __('web::hajj_account.password.new') }}</label>
                                    <div class="hajj-acc2-pw-wrap">
                                        <input id="hajj-acc-new-pw" name="password" type="password" class="hajj-acc2-input" placeholder="{{ __('web::hajj_account.password.placeholder_new') }}" autocomplete="new-password">
                                        <button type="button" class="hajj-acc2-pw-toggle" data-toggle-pass="hajj-acc-new-pw" aria-label="{{ __('web::hajj_auth.login-form.show-password') }}"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                    @error('password')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-conf-pw"><i class="fas fa-check-circle" aria-hidden="true"></i>{{ __('web::hajj_account.password.confirm') }}</label>
                                    <div class="hajj-acc2-pw-wrap">
                                        <input id="hajj-acc-conf-pw" name="password_confirmation" type="password" class="hajj-acc2-input" placeholder="{{ __('web::hajj_account.password.placeholder_confirm') }}" autocomplete="new-password">
                                        <button type="button" class="hajj-acc2-pw-toggle" data-toggle-pass="hajj-acc-conf-pw" aria-label="{{ __('web::hajj_auth.login-form.show-password') }}"><i class="fas fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="hajj-acc2-actions">
                                <button type="submit" class="hajj-acc2-btn-primary"><i class="fas fa-key" aria-hidden="true"></i>{{ __('web::hajj_account.password.change') }}</button>
                            </div>
                        </form>

                        <div class="hajj-acc2-delete-section">
                            <h4>{{ __('web::hajj_account.delete.title') }}</h4>
                            <p class="hajj-acc2-muted-hint">{{ __('web::hajj_account.delete.hint') }}</p>
                            <form method="post" action="{{ route('hajj.account.destroy') }}" data-hajj-delete-account>
                                @csrf
                                @method('DELETE')
                                <div class="hajj-acc2-form-group">
                                    <label class="hajj-acc2-label" for="hajj-acc-del-pw">{{ __('web::hajj_account.delete.password') }}</label>
                                    <input id="hajj-acc-del-pw" name="delete_password" type="password" class="hajj-acc2-input" placeholder="{{ __('web::hajj_account.delete.placeholder') }}" autocomplete="current-password">
                                    @error('delete_password')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                                </div>
                                <button type="submit" class="hajj-acc2-btn-danger">{{ __('web::hajj_account.delete.button') }}</button>
                            </form>
                        </div>
                    </div>

                    <div
                        class="hajj-acc2-panel {{ $activeTab === 'preferences' ? 'is-active' : '' }}"
                        data-hajj-tab-panel="preferences"
                        role="tabpanel"
                        @if ($activeTab !== 'preferences') hidden @endif
                    >
                        <form method="post" action="{{ route('hajj.account.preferences.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="hajj-acc2-settings-block">
                                <h4><i class="fas fa-language" aria-hidden="true"></i>{{ __('web::hajj_account.settings.language_title') }}</h4>
                                <div class="hajj-acc2-lang-options">
                                    @foreach ($storeLocaleOptions as $opt)
                                        @php $code = strtolower((string) ($opt['value'] ?? '')); @endphp
                                        <label class="hajj-acc2-lang-option">
                                            <input type="radio" name="locale" value="{{ $code }}" @checked(old('locale', $currentLocaleCode) === $code)>
                                            <span>{{ $opt['title'] ?? $code }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('locale')<p class="hajj-acc2-form-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="hajj-acc2-actions">
                                <button type="submit" class="hajj-acc2-btn-primary"><i class="fas fa-save" aria-hidden="true"></i>{{ __('web::hajj_account.settings.save') }}</button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="hajj-acc2-panel {{ $activeTab === 'favorites' ? 'is-active' : '' }}"
                        data-hajj-tab-panel="favorites"
                        role="tabpanel"
                        @if ($activeTab !== 'favorites') hidden @endif
                    >
                        <div class="hajj-acc2-fav-header">
                            <h3 class="hajj-acc2-fav-title">
                                {{ __('web::hajj_account.favorites.title') }}
                            </h3>
                            @if (count($favoriteDuas) > 0)
                                <form method="post" action="{{ route('hajj.account.favorites.clear') }}" class="hap-inline-fav-clear" data-hajj-fav-clear-form>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hajj-acc2-btn-danger hajj-acc2-btn-danger--compact">
                                        {{ __('web::hajj_account.favorites.clear_all') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="hajj-acc2-fav-grid">
                            @forelse ($favoriteDuas as $fav)
                                <div class="hajj-acc2-fav-card">
                                    <form method="post" action="{{ route('hajj.account.favorites.destroy', $fav['id']) }}" class="hap-inline-fav-remove" data-hajj-fav-remove-form>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hajj-acc2-fav-remove" aria-label="{{ __('web::hajj_account.favorites.remove_confirm') }}">
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                    <div class="hajj-acc2-fav-text">{{ $fav['text'] }}</div>
                                    <div class="hajj-acc2-fav-ref">{{ $fav['reference'] }}</div>
                                </div>
                            @empty
                                <div class="hajj-acc2-fav-empty">
                                    <i class="fas fa-heart-broken" aria-hidden="true"></i>
                                    <p>{{ __('web::hajj_account.favorites.empty') }}</p>
                                    <p class="hajj-acc2-fav-empty-hint">{{ __('web::hajj_account.favorites.empty_hint') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-web::layouts>
