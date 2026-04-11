@push('meta')
    <meta name="description" content="{{ __('web::app.adhkar.meta_description') }}" />
@endpush

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .web-adhkar-page {
            /* Tabs/cards: same identity as header (--shop-*) + optional config/web.identity overrides on <html> */
            --adhkar-forest: var(--shop-primary);
            --adhkar-forest-mid: color-mix(in srgb, var(--shop-primary) 88%, black);
            --adhkar-forest-soft: var(--shop-accent);
            --adhkar-gold: var(--portal-gold);
            --adhkar-gold-bright: var(--portal-gold-bright);
            --adhkar-parchment: color-mix(in srgb, var(--shop-primary) 5%, #fdfcfa);
            --adhkar-ink: var(--portal-ink);
            --adhkar-muted: var(--portal-muted);
            --adhkar-line: color-mix(in srgb, var(--shop-primary) 18%, #ffffff);
            --adhkar-shadow: 0 1px 3px color-mix(in srgb, var(--shop-primary) 8%, transparent),
                0 12px 28px color-mix(in srgb, var(--shop-primary) 14%, transparent);
            --adhkar-shadow-hover: 0 8px 24px color-mix(in srgb, var(--shop-primary) 14%, transparent),
                0 24px 48px color-mix(in srgb, var(--shop-primary) 12%, transparent);
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
            .web-adhkar-card { transition: none; }
        }

        .web-adhkar-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: clamp(1.25rem, 2.5vw, 1.75rem);
        }
        @media (min-width: 640px) {
            .web-adhkar-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .web-adhkar-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: clamp(1.35rem, 2vw, 2rem);
            }
        }

        .web-adhkar-card {
            position: relative;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 1.25rem;
            padding: 0;
            border: 1px solid var(--adhkar-line);
            box-shadow: var(--adhkar-shadow);
            transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.28s ease, border-color 0.2s ease;
            overflow: hidden;
            min-height: 0;
        }
        /* Accent stripe on logical inline-start (right in RTL, left in LTR) */
        .web-adhkar-card::before {
            content: '';
            position: absolute;
            inset-inline-start: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(
                180deg,
                var(--adhkar-gold) 0%,
                var(--adhkar-gold-bright) 45%,
                color-mix(in srgb, var(--adhkar-gold) 35%, transparent) 100%
            );
            border-radius: 1px;
            opacity: 0.95;
        }
        .web-adhkar-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--adhkar-shadow-hover);
            border-color: color-mix(in srgb, var(--adhkar-gold) 30%, transparent);
        }
        .web-adhkar-card__inner {
            padding-block: clamp(1.25rem, 2.5vw, 1.75rem) clamp(1.5rem, 3vw, 2.25rem);
            padding-inline: clamp(1.35rem, 3vw, 2rem);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .web-adhkar-card__head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--adhkar-line);
        }
        .web-adhkar-card__title {
            font-size: clamp(1.125rem, 2.2vw, 1.375rem);
            font-weight: 700;
            line-height: 1.45;
            color: var(--adhkar-forest);
        }
        .web-adhkar-card__badge {
            flex-shrink: 0;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--adhkar-forest-soft);
            background: #fff;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            border: 1px solid var(--adhkar-line);
        }

        .web-adhkar-card__quote {
            position: relative;
            flex: 1;
            margin-bottom: 1rem;
        }
        .web-adhkar-card__text {
            position: relative;
            z-index: 1;
            font-size: clamp(1.0625rem, 2vw, 1.25rem);
            font-weight: 500;
            line-height: 2.1;
            color: var(--adhkar-ink);
            font-feature-settings: 'kern' 1, 'liga' 1;
        }
        .web-adhkar-card__ref {
            font-size: 0.875rem;
            line-height: 1.55;
            color: var(--adhkar-muted);
            padding-block: 0.75rem;
            padding-inline: 1rem 0;
            border-radius: 0;
            background: transparent;
            border-inline-start: 3px solid color-mix(in srgb, var(--adhkar-gold) 45%, transparent);
            margin-bottom: 0;
            font-style: normal;
        }

        .web-adhkar-card__actions {
            display: flex;
            align-items: stretch;
            gap: 0.75rem;
            margin-top: auto;
            padding-block: 1.25rem clamp(1.35rem, 3vw, 2rem);
            padding-inline: clamp(1.35rem, 3vw, 2rem);
            background: transparent;
            border-top: 1px solid var(--adhkar-line);
        }
        .web-adhkar-btn-copy {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 3rem;
            padding: 0.65rem 1.15rem;
            border: none;
            border-radius: 0.65rem;
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            color: var(--adhkar-forest);
            background: #fff;
            border: 1px solid var(--adhkar-line);
            box-shadow: 0 1px 2px color-mix(in srgb, var(--shop-primary) 6%, transparent);
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
        .web-adhkar-btn-copy:hover {
            background: var(--adhkar-forest-mid);
            border-color: var(--adhkar-forest-mid);
            color: #fff;
            box-shadow: 0 4px 12px color-mix(in srgb, var(--shop-primary) 22%, transparent);
        }
        .web-adhkar-btn-copy:focus-visible {
            outline: 2px solid var(--adhkar-gold);
            outline-offset: 2px;
        }
        .web-adhkar-btn-fav {
            flex-shrink: 0;
            min-height: 3rem;
            padding: 0.65rem 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--adhkar-line);
            border-radius: 0.65rem;
            background: #fff;
            color: var(--adhkar-forest-mid);
            font-size: 0.8125rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        .web-adhkar-btn-fav:hover {
            background: color-mix(in srgb, var(--adhkar-gold) 14%, white);
            border-color: color-mix(in srgb, var(--adhkar-gold) 38%, transparent);
            color: var(--adhkar-forest-mid);
        }
        .web-adhkar-btn-fav.is-saved {
            background: rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.35);
            color: #b71c1c;
        }
        .web-adhkar-btn-fav:focus-visible {
            outline: 2px solid var(--adhkar-gold);
            outline-offset: 2px;
        }

        .web-adhkar-toast {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            max-width: min(92vw, 22rem);
            padding: 0.85rem 1.25rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.35;
            text-align: center;
            z-index: 1080;
            opacity: 0;
            pointer-events: none;
            color: var(--adhkar-gold-bright);
            background: color-mix(in srgb, var(--shop-primary) 92%, black);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid color-mix(in srgb, var(--adhkar-gold-bright) 28%, transparent);
            box-shadow: 0 8px 32px color-mix(in srgb, var(--shop-primary) 32%, transparent);
            transition: opacity 0.35s ease, transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .web-adhkar-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    </style>
@endpush

<x-web::layouts :title="$pageTitle">
    <x-web::layouts.inner-page-hero page-key="adhkar" />

    <div class="web-adhkar-page">
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
    </div>

    <div id="web-adhkar-toast" class="web-adhkar-toast" role="status" aria-live="polite"></div>

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
                function toast(msg) {
                    var el = document.getElementById('web-adhkar-toast');
                    if (!el) return;
                    el.textContent = msg;
                    el.classList.add('show');
                    clearTimeout(el._t);
                    el._t = setTimeout(function () { el.classList.remove('show'); }, 2200);
                }
                document.querySelector('.web-adhkar-page').addEventListener('click', function (e) {
                    var copyBtn = e.target.closest('[data-web-adhkar-copy]');
                    if (copyBtn) {
                        var card = copyBtn.closest('.web-adhkar-card');
                        var textEl = card && card.querySelector('.web-adhkar-card__text');
                        var text = textEl ? textEl.innerText.trim() : '';
                        if (text && navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).then(function () {
                                toast(@json(__('web::app.adhkar.toast_copied')));
                            }).catch(function () { toast(@json(__('web::app.adhkar.toast_copy_failed'))); });
                        }
                        return;
                    }
                    var favBtn = e.target.closest('[data-web-adhkar-fav]');
                    if (favBtn) {
                        favBtn.classList.toggle('is-saved');
                        toast(favBtn.classList.contains('is-saved')
                            ? @json(__('web::app.adhkar.toast_fav_added'))
                            : @json(__('web::app.adhkar.toast_fav_removed')));
                    }
                });
            })();
        </script>
    @endpush
</x-web::layouts>
