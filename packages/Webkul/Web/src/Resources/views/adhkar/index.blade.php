@push('meta')
    <meta name="description" content="{{ __('web::app.adhkar.meta_description') }}" />
@endpush

@include('web::components.adhkar.card-actions-script')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .web-adhkar-page.web-adhkar-ui {
            font-family: 'Cairo', var(--shop-font-sans), ui-sans-serif, system-ui, sans-serif;
            color: var(--adhkar-ink);
            background: #fff;
            width: 100%;
            max-width: 100%;
            position: relative;
            overflow-x: visible;
        }
        .web-adhkar-page .web-adhkar-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 1.75rem clamp(1rem, 4vw, 3rem) 3.5rem;
        }
        @media (min-width: 768px) {
            .web-adhkar-page .web-adhkar-container { padding-top: 2.25rem; padding-bottom: 4rem; }
        }

        /* Tabs: floating bar + scroll on small screens */
        .web-adhkar-tabs-wrap {
            margin-bottom: 2.25rem;
            padding: 0.35rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: var(--adhkar-shadow);
        }
        @media (max-width: 1023px) {
            .web-adhkar-tabs-wrap {
                position: sticky;
                top: calc(env(safe-area-inset-top, 0px) + 4rem);
                z-index: 100;
                background: rgba(255, 255, 255, 0.94);
            }
        }
        .web-adhkar-tabs {
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-start;
            gap: 0.35rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            overflow-y: hidden;
            scrollbar-width: thin;
            scrollbar-color: color-mix(in srgb, var(--adhkar-gold) 42%, transparent) transparent;
            padding-bottom: 2px;
            scroll-padding-inline: 0.5rem;
        }
        @media (min-width: 1024px) {
            .web-adhkar-tabs { flex-wrap: wrap; justify-content: center; overflow-x: visible; }
        }
        .web-adhkar-tab-btn {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9375rem;
            letter-spacing: 0.01em;
            color: var(--adhkar-forest-mid);
            background: transparent;
            padding: 0.75rem 1.35rem;
            border-radius: 0.65rem;
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }
        .web-adhkar-tab-btn:hover:not(.active) {
            background: color-mix(in srgb, var(--shop-primary) 8%, white);
            color: var(--adhkar-forest);
        }
        .web-adhkar-tab-btn.active {
            background: linear-gradient(145deg, var(--adhkar-forest-mid) 0%, var(--adhkar-forest) 100%);
            color: var(--adhkar-gold-bright);
            box-shadow: 0 4px 14px color-mix(in srgb, var(--shop-primary) 28%, transparent);
        }
        .web-adhkar-tab-btn:focus-visible {
            outline: 2px solid var(--adhkar-gold);
            outline-offset: 2px;
        }

        .web-adhkar-tab-panel { display: none; }
        .web-adhkar-tab-panel.active { display: block; animation: webAdhkarFade 0.45s cubic-bezier(0.22, 1, 0.36, 1); }
        @keyframes webAdhkarFade {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .web-adhkar-tab-panel.active { animation: none; }
        }
    </style>
@endpush

<x-web::layouts :title="$pageTitle">
    <x-web::layouts.inner-page-hero page-key="adhkar" />

    <div class="web-adhkar-page web-adhkar-ui">
        <div class="web-adhkar-container">
            @if (count($duaTabs) === 0)
                <div class="web-page-empty" role="status" aria-live="polite">
                    <h2 class="web-page-empty__title">
                        {{ __('web::app.adhkar.empty_title') }}
                    </h2>
                    <div class="web-page-empty__gold-line" aria-hidden="true"></div>
                    <p class="web-page-empty__text">
                        {{ __('web::app.adhkar.empty_hint') }}
                    </p>
                    <a href="{{ route('web.home.index') }}" class="web-page-empty__btn">
                        {{ __('web::app.adhkar.empty_btn_home') }}
                    </a>
                </div>
            @else
            <div class="web-adhkar-tabs-wrap">
                <div class="web-adhkar-tabs" role="tablist" aria-label="{{ __('web::app.adhkar.tabs_aria') }}">
                    @foreach ($duaTabs as $idx => $tab)
                        @php
                            $tabId = $tab['tab_id'];
                            $isFirst = $idx === 0;
                        @endphp
                        <button
                            type="button"
                            role="tab"
                            class="web-adhkar-tab-btn {{ $isFirst ? 'active' : '' }}"
                            data-web-adhkar-tab="{{ $tabId }}"
                            aria-selected="{{ $isFirst ? 'true' : 'false' }}"
                            id="web-adhkar-tab-{{ $tabId }}"
                            aria-controls="web-adhkar-panel-{{ $tabId }}"
                        >
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach ($duaTabs as $idx => $tab)
                @php
                    $tabId = $tab['tab_id'];
                    $cards = $tab['cards'] ?? [];
                    $isFirst = $idx === 0;
                @endphp
                <div
                    id="web-adhkar-panel-{{ $tabId }}"
                    role="tabpanel"
                    class="web-adhkar-tab-panel {{ $isFirst ? 'active' : '' }}"
                    aria-hidden="{{ $isFirst ? 'false' : 'true' }}"
                    aria-labelledby="web-adhkar-tab-{{ $tabId }}"
                >
                    <div class="web-adhkar-grid">
                        @foreach ($cards as $card)
                            <article class="web-adhkar-card">
                                <div class="web-adhkar-card__inner">
                                    <div class="web-adhkar-card__head">
                                        <div class="web-adhkar-card__title">
                                            {{ $card['title'] }}
                                        </div>
                                        <span class="web-adhkar-card__badge">{{ $card['badge'] }}</span>
                                    </div>
                                    <div class="web-adhkar-card__quote">
                                        <div class="web-adhkar-card__text">{{ $card['text'] }}</div>
                                    </div>
                                    <div class="web-adhkar-card__ref">{{ $card['reference'] }}</div>
                                </div>
                                <div class="web-adhkar-card__actions">
                                    <button type="button" class="web-adhkar-btn-copy" data-web-adhkar-copy>
                                        {{ __('web::app.adhkar.copy') }}
                                    </button>
                                    <button type="button" class="web-adhkar-btn-fav" data-web-adhkar-fav aria-label="{{ __('web::app.adhkar.favorite_aria') }}">
                                        {{ __('web::app.adhkar.favorite_btn') }}
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        <div id="web-adhkar-toast" class="web-adhkar-toast" role="status" aria-live="polite"></div>
    </div>

    @push('scripts')
        <script>
            (function () {
                var tabBtns = document.querySelectorAll('[data-web-adhkar-tab]');
                var panels = {};
                document.querySelectorAll('.web-adhkar-tab-panel').forEach(function (p) {
                    var id = p.id && p.id.replace('web-adhkar-panel-', '');
                    if (id) panels[id] = p;
                });
                function scrollActiveTabIntoView(behavior) {
                    var strip = document.querySelector('.web-adhkar-tabs');
                    var active = strip && strip.querySelector('.web-adhkar-tab-btn.active');
                    if (!active || !strip || typeof active.scrollIntoView !== 'function') return;
                    var be = behavior === 'smooth' ? 'smooth' : 'auto';
                    try {
                        active.scrollIntoView({
                            inline: 'center',
                            block: 'nearest',
                            behavior: be,
                        });
                    } catch (e) {
                        active.scrollIntoView(true);
                    }
                }
                function showTab(id) {
                    tabBtns.forEach(function (b) {
                        var on = b.getAttribute('data-web-adhkar-tab') === id;
                        b.classList.toggle('active', on);
                        b.setAttribute('aria-selected', on ? 'true' : 'false');
                    });
                    Object.keys(panels).forEach(function (k) {
                        var p = panels[k];
                        var on = k === id;
                        p.classList.toggle('active', on);
                        p.setAttribute('aria-hidden', on ? 'false' : 'true');
                    });
                    requestAnimationFrame(function () {
                        scrollActiveTabIntoView('smooth');
                    });
                }
                tabBtns.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        showTab(btn.getAttribute('data-web-adhkar-tab'));
                    });
                });
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    scrollActiveTabIntoView('instant');
                } else {
                    requestAnimationFrame(function () {
                        scrollActiveTabIntoView('instant');
                    });
                }
            })();
        </script>
    @endpush
</x-web::layouts>
