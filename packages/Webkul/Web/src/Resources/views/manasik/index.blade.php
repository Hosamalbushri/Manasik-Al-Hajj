@push('meta')
    <meta name="description" content="{{ __('web::app.manasik.meta_description') }}" />
@endpush

@php
    $manasikUi = __('web::app.manasik.ui');
    $manasikEnc = __('web::app.manasik.encouragement');
    if (! is_array($manasikUi)) {
        $manasikUi = [];
    }
    if (! is_array($manasikEnc)) {
        $manasikEnc = [];
    }
    $stepCount = count($steps);
    $manasikReturnPath = parse_url(route('web.manasik.index'), PHP_URL_PATH);
    if (! is_string($manasikReturnPath) || $manasikReturnPath === '') {
        $manasikReturnPath = '/manasik';
    }
    $guestLoginUrl = route('hajj.session.create', ['redirect' => $manasikReturnPath]);
@endphp

<x-web::layouts :title="$pageTitle">
    <x-web::layouts.inner-page-hero page-key="services" />

    <div
        class="web-manasik-guide"
        data-storage-key="web_manasik_rituals_v1"
        data-hajj-auth="{{ ($hajjLoggedIn ?? false) ? '1' : '0' }}"
        data-save-progress-url="{{ $saveManasikProgressUrl ?? '' }}"
        data-guest-completion-url="{{ $guestManasikCompletionUrl ?? '' }}"
        data-server-progress='@json($manasikServerProgress ?? null)'
        data-i18n-ui="{{ json_encode($manasikUi, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
        data-i18n-encouragement="{{ json_encode($manasikEnc, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
        data-step-count="{{ $stepCount }}"
        aria-label="{{ __('web::app.manasik.aria_page') }}"
    >
        <div class="web-manasik-guide__inner">
            @guest('hajj')
                @if (trim((string) ($manasikUi['guest_save_hint'] ?? '')) !== '')
                    <div
                        class="web-manasik-guide__guest-hint"
                        data-manasik-guest-hint
                        role="region"
                        aria-label="{{ __('web::app.manasik.aria_guest_hint') }}"
                    >
                        <p class="web-manasik-guide__guest-hint-text">{{ $manasikUi['guest_save_hint'] }}</p>
                        <div class="web-manasik-guide__guest-hint-actions">
                            <a href="{{ $guestLoginUrl }}" class="web-manasik-guide__guest-hint-link">
                                {{ $manasikUi['guest_login'] ?? '' }}
                            </a>
                            <button type="button" class="web-manasik-guide__guest-hint-dismiss" data-manasik-guest-dismiss>
                                {{ $manasikUi['guest_dismiss'] ?? '' }}
                            </button>
                        </div>
                    </div>
                @endif
            @endguest
            <div class="web-manasik-guide__layout">
                <aside
                    class="web-manasik-guide__sidebar"
                    aria-label="{{ __('web::app.manasik.aria_progress') }}"
                >
                    <section class="web-manasik-guide__tracker">
                        <div class="web-manasik-guide__completion">
                            <div class="web-manasik-guide__completion-text">
                                <i class="fas fa-chart-line" aria-hidden="true"></i>
                                <span>{{ __('web::app.manasik.progress_heading') }}</span>
                            </div>
                            <div class="web-manasik-guide__completion-percent" data-manasik-percent>0%</div>
                        </div>

                        <div class="web-manasik-guide__tabs-progress">
                            <div class="web-manasik-guide__bar-wrap web-manasik-guide__bar-wrap--tabs">
                                <div class="web-manasik-guide__bar-fill" data-manasik-bar style="width:0%"></div>
                            </div>

                            <div class="web-manasik-guide__steps-scroll web-manasik-guide__steps-scroll--stack" data-manasik-steps-scroll>
                                <div
                                    class="web-manasik-guide__steps web-manasik-guide__steps--stack"
                                    role="tablist"
                                    aria-label="{{ __('web::app.manasik.aria_steps') }}"
                                >
                                    @foreach ($steps as $i => $step)
                                        @php
                                            $tab = is_array($step) ? ($step['tab_label'] ?? ($step['title'] ?? '')) : '';
                                        @endphp
                                        <button
                                            type="button"
                                            class="web-manasik-guide__step"
                                            data-manasik-step="{{ $i }}"
                                            role="tab"
                                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                                        >
                                            <span class="web-manasik-guide__tab-title">{{ $tab }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </section>
                </aside>

                <div
                    class="web-manasik-guide__main"
                    aria-live="polite"
                    aria-label="{{ __('web::app.manasik.aria_rituals') }}"
                >
                    <header class="web-manasik-guide__main-head">
                        <p class="web-manasik-guide__eyebrow">{{ $manasikUi['journey_label'] ?? '' }}</p>
                        <p class="web-manasik-guide__step-meta" data-manasik-step-meta></p>
                    </header>
                    <div class="web-manasik-guide__grid" data-manasik-rituals>
                        @foreach ($steps as $i => $step)
                            @continue(! is_array($step))
                            @php
                                $title = $step['title'] ?? '';
                                $subtitle = $step['subtitle'] ?? '';
                                $badge = $step['badge'] ?? '';
                                $description = $step['description'] ?? '';
                                $duaLabel = trim((string) ($step['dua_label'] ?? ''));
                                $duaText = trim((string) ($step['dua_text'] ?? ''));
                                $duaRef = trim((string) ($step['dua_reference'] ?? ''));
                                $infoItems = is_array($step['info_items'] ?? null) ? $step['info_items'] : [];
                                $stepDuas = is_array($step['duas'] ?? null) ? $step['duas'] : [];
                            @endphp
                            <article
                                id="web-manasik-step-{{ $i }}"
                                class="web-manasik-guide__card"
                                data-manasik-card="{{ $i }}"
                                data-ritual-id="{{ $i }}"
                                @if ($i !== 0) hidden @endif
                            >
                                <header class="web-manasik-guide__card-head">
                                    <div class="web-manasik-guide__card-num" aria-hidden="true">{{ $i + 1 }}</div>
                                    <div class="web-manasik-guide__card-titles">
                                        <h2 class="web-manasik-guide__card-title">{{ $title }}</h2>
                                        @if ($subtitle !== '')
                                            <p class="web-manasik-guide__card-sub">{{ $subtitle }}</p>
                                        @endif
                                    </div>
                                    @if ($badge !== '')
                                        <span class="web-manasik-guide__card-badge">{{ $badge }}</span>
                                    @endif
                                    <button
                                        type="button"
                                        class="web-manasik-guide__btn-done"
                                        data-manasik-toggle="{{ $i }}"
                                    >
                                        {{ $manasikUi['mark_complete'] ?? '' }}
                                    </button>
                                </header>
                                <div class="web-manasik-guide__card-body">
                                    @if ($description !== '')
                                        <section class="web-manasik-guide__section web-manasik-guide__section--detail" aria-labelledby="web-manasik-detail-{{ $i }}">
                                            <h3 class="web-manasik-guide__section-title" id="web-manasik-detail-{{ $i }}">
                                                {{ $manasikUi['section_detail'] ?? '' }}
                                            </h3>
                                            <div class="web-manasik-guide__desc">{!! nl2br(e($description)) !!}</div>
                                        </section>
                                    @endif

                                    @if (count($stepDuas) > 0)
                                        <section class="web-manasik-guide__section web-manasik-guide__section--dua" aria-labelledby="web-manasik-dua-h-{{ $i }}">
                                            <h3 class="web-manasik-guide__section-title" id="web-manasik-dua-h-{{ $i }}">
                                                {{ $manasikUi['section_dua'] ?? '' }}
                                            </h3>
                                            <div class="web-manasik-guide__dua-cards">
                                                @foreach ($stepDuas as $di => $duaRow)
                                                    @php
                                                        $dl = trim((string) ($duaRow['dua_label'] ?? ''));
                                                        $dt = trim((string) ($duaRow['dua_text'] ?? ''));
                                                        $dr = trim((string) ($duaRow['dua_reference'] ?? ''));
                                                        $duaDomId = 'web-manasik-dua-'.$i.'-'.$di;
                                                    @endphp
                                                    @if ($dt !== '')
                                                        <article class="web-manasik-guide__dua-card">
                                                            @if ($dl !== '')
                                                                <header class="web-manasik-guide__dua-card-head">
                                                                    <h4 class="web-manasik-guide__dua-card-title">{{ $dl }}</h4>
                                                                </header>
                                                            @endif
                                                            <div class="web-manasik-guide__dua-card-body">
                                                                <blockquote class="web-manasik-guide__dua-text" id="{{ $duaDomId }}">{{ $dt }}</blockquote>
                                                            </div>
                                                            <footer class="web-manasik-guide__dua-card-foot">
                                                                @if ($dr !== '')
                                                                    <cite class="web-manasik-guide__dua-ref">{{ $dr }}</cite>
                                                                @endif
                                                                <button
                                                                    type="button"
                                                                    class="web-manasik-guide__btn-copy"
                                                                    data-manasik-copy="{{ $duaDomId }}"
                                                                    aria-label="{{ $manasikUi['copy_dua_aria'] ?? ($manasikUi['copy_dua'] ?? '') }}"
                                                                >
                                                                    {{ $manasikUi['copy_dua'] ?? '' }}
                                                                </button>
                                                            </footer>
                                                        </article>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </section>
                                    @elseif ($duaText !== '')
                                        <section class="web-manasik-guide__section web-manasik-guide__section--dua" aria-labelledby="web-manasik-dua-h-{{ $i }}">
                                            <h3 class="web-manasik-guide__section-title" id="web-manasik-dua-h-{{ $i }}">
                                                {{ $manasikUi['section_dua'] ?? '' }}
                                            </h3>
                                            <div class="web-manasik-guide__dua-cards web-manasik-guide__dua-cards--single">
                                                <article class="web-manasik-guide__dua-card">
                                                    @if ($duaLabel !== '')
                                                        <header class="web-manasik-guide__dua-card-head">
                                                            <h4 class="web-manasik-guide__dua-card-title">{{ $duaLabel }}</h4>
                                                        </header>
                                                    @endif
                                                    <div class="web-manasik-guide__dua-card-body">
                                                        <blockquote class="web-manasik-guide__dua-text" id="web-manasik-dua-{{ $i }}">{{ $duaText }}</blockquote>
                                                    </div>
                                                    <footer class="web-manasik-guide__dua-card-foot">
                                                        @if ($duaRef !== '')
                                                            <cite class="web-manasik-guide__dua-ref">{{ $duaRef }}</cite>
                                                        @endif
                                                        <button
                                                            type="button"
                                                            class="web-manasik-guide__btn-copy"
                                                            data-manasik-copy="web-manasik-dua-{{ $i }}"
                                                            aria-label="{{ $manasikUi['copy_dua_aria'] ?? ($manasikUi['copy_dua'] ?? '') }}"
                                                        >
                                                            {{ $manasikUi['copy_dua'] ?? '' }}
                                                        </button>
                                                    </footer>
                                                </article>
                                            </div>
                                        </section>
                                    @endif

                                    @if (count($infoItems) > 0)
                                        @php
                                            $infoLines = [];
                                            foreach ($infoItems as $item) {
                                                if (! is_array($item)) {
                                                    continue;
                                                }
                                                $tx = trim((string) ($item['text'] ?? ''));
                                                if ($tx !== '') {
                                                    $infoLines[] = $tx;
                                                }
                                            }
                                        @endphp
                                        @if (count($infoLines) > 0)
                                            <section class="web-manasik-guide__section web-manasik-guide__section--notes" aria-labelledby="web-manasik-notes-{{ $i }}">
                                                <h3 class="web-manasik-guide__section-title" id="web-manasik-notes-{{ $i }}">
                                                    {{ $manasikUi['section_notes'] ?? '' }}
                                                </h3>
                                                <div
                                                    class="web-manasik-guide__info-cards{{ count($infoLines) === 1 ? ' web-manasik-guide__info-cards--single' : '' }}"
                                                    role="list"
                                                >
                                                    @foreach ($infoLines as $line)
                                                        <article class="web-manasik-guide__info-card" role="listitem">
                                                            <div class="web-manasik-guide__info-card-body">
                                                                <p class="web-manasik-guide__info-card-text">{{ $line }}</p>
                                                            </div>
                                                        </article>
                                                    @endforeach
                                                </div>
                                            </section>
                                        @endif
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="web-manasik-guide__below-card">
                        <nav class="web-manasik-guide__nav web-manasik-guide__nav--below-card" aria-label="{{ __('web::app.manasik.aria_page') }}">
                            <div class="web-manasik-guide__nav-pair">
                                <button type="button" class="web-manasik-guide__nav-btn web-manasik-guide__nav-btn--ghost" data-manasik-prev>
                                    <span>{{ $manasikUi['prev'] ?? '' }}</span>
                                </button>
                                <button type="button" class="web-manasik-guide__nav-btn" data-manasik-next>
                                    <span>{{ $manasikUi['next'] ?? '' }}</span>
                                </button>
                            </div>
                            <button type="button" class="web-manasik-guide__nav-btn web-manasik-guide__nav-btn--outline" data-manasik-reset>
                                {{ $manasikUi['reset'] ?? '' }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-web::layouts>
