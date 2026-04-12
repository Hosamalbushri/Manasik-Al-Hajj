@php
    $cards = is_array($cards ?? null) ? $cards : [];
    $cardDetails = is_array($cardDetails ?? null) ? $cardDetails : [];
    $idSuffix = (string) ($idSuffix ?? '');
@endphp

@if (count($cards) > 0)
    <div
        data-web-maps-cards
        data-details='@json($cardDetails)'
    >
        <div class="web-maps-locations-page__cards-grid">
            @foreach ($cards as $card)
                @php
                    $card = is_array($card) ? $card : [];
                    $slug = (string) ($card['slug'] ?? '');
                    $baseMapId = (string) ($card['map_id'] ?? 'map');
                    $mapId = $baseMapId.$idSuffix;
                    $title = $card['title'] ?? '';
                    $title = is_string($title) ? $title : '';
                    $detailBody = trim((string) ($card['detail_alert'] ?? ''));
                    $features = $card['features'] ?? [];
                    $features = is_array($features) ? $features : [];
                @endphp
                <article class="web-maps-locations-page__card">
                    <div class="web-maps-locations-page__card-image">
                        <img
                            src="{{ $card['image'] ?? '' }}"
                            alt=""
                            loading="lazy"
                            decoding="async"
                        >
                        @if (! empty($card['badge']))
                            <div class="web-maps-locations-page__card-badge">
                                {{ $card['badge'] }}
                            </div>
                        @endif
                    </div>
                    <div class="web-maps-locations-page__card-content">
                        @if ($title !== '')
                            <h3 class="web-maps-locations-page__location-name">{{ $title }}</h3>
                        @endif
                        @if (! empty($card['description']))
                            <p class="web-maps-locations-page__location-description">{{ $card['description'] }}</p>
                        @endif
                        @if (count($features) > 0)
                            <div class="web-maps-locations-page__features">
                                <div class="web-maps-locations-page__features-title">
                                    {{ __('web::app.maps.features_title') }}
                                </div>
                                <ul class="web-maps-locations-page__features-list">
                                    @foreach ($features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="web-maps-locations-page__map" id="{{ $mapId }}">
                            @if (! empty($card['embed']))
                                <iframe
                                    class="web-maps-locations-page__map-iframe"
                                    src="{{ $card['embed'] }}"
                                    title="{{ $title !== '' ? $title : __('web::app.maps.meta_title') }}"
                                    allowfullscreen
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                            @endif
                        </div>
                        <div class="web-maps-locations-page__card-buttons">
                            @if ($detailBody !== '')
                                <button
                                    type="button"
                                    class="web-maps-locations-page__btn-details"
                                    data-web-maps-detail="{{ $slug }}"
                                    data-web-maps-detail-title="{{ $title }}"
                                >
                                    {{ __('web::app.maps.btn_details') }}
                                </button>
                            @endif
                            @if (! empty($card['embed']))
                                <button
                                    type="button"
                                    class="web-maps-locations-page__btn-map"
                                    data-web-maps-toggle="{{ $mapId }}"
                                    data-label-show="{{ __('web::app.maps.btn_show_map') }}"
                                    data-label-hide="{{ __('web::app.maps.btn_hide_map') }}"
                                >
                                    {{ __('web::app.maps.btn_show_map') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    @pushOnce('scripts', 'web-maps-location-cards-interactions')
        <script>
            (function () {
                function initMapsCardsRoot(root) {
                    if (!root || root.getAttribute('data-web-maps-inited') === '1') {
                        return;
                    }
                    root.setAttribute('data-web-maps-inited', '1');

                    var details = {};
                    try {
                        details = JSON.parse(root.getAttribute('data-details') || '{}');
                    } catch (e) {}

                    root.querySelectorAll('[data-web-maps-detail]').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            var slug = btn.getAttribute('data-web-maps-detail');
                            var title = btn.getAttribute('data-web-maps-detail-title') || '';
                            var body = (details && details[slug]) || '';
                            window.alert(title + '\n\n' + body);
                        });
                    });

                    root.querySelectorAll('[data-web-maps-toggle]').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            var id = btn.getAttribute('data-web-maps-toggle');
                            var el = document.getElementById(id);
                            if (!el) return;
                            var showLabel = btn.getAttribute('data-label-show') || '';
                            var hideLabel = btn.getAttribute('data-label-hide') || '';
                            el.classList.toggle('web-maps-locations-page__map--open');
                            var open = el.classList.contains('web-maps-locations-page__map--open');
                            btn.textContent = open ? hideLabel : showLabel;
                        });
                    });
                }

                function boot() {
                    document.querySelectorAll('[data-web-maps-cards]').forEach(initMapsCardsRoot);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', boot);
                } else {
                    boot();
                }
            })();
        </script>
    @endPushOnce
@endif
