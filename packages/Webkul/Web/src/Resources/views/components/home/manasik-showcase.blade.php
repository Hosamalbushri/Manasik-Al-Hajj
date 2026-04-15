@props([
    'options' => [],
    'sectionId' => 0,
    'ariaLabel' => null,
])

@php
    use Webkul\Manasik\Repositories\HajjRiteRepository;

    $heading = trim((string) ($options['heading'] ?? ''));
    $subheading = trim((string) ($options['subheading'] ?? ''));
    $limit = (int) ($options['limit'] ?? 6);
    $limit = max(1, min(50, $limit));
    $showMore = array_key_exists('show_more', $options)
        ? filter_var($options['show_more'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $moreUrl = trim((string) ($options['more_url'] ?? ''));
    if ($moreUrl === '' && \Illuminate\Support\Facades\Route::has('web.manasik.index')) {
        $moreUrl = route('web.manasik.index');
    }

    $detailBaseUrl = \Illuminate\Support\Facades\Route::has('web.manasik.index')
        ? route('web.manasik.index')
        : '#';

    $cards = app(HajjRiteRepository::class)->getHomePreviewRites($limit);
    $sectionAria = $ariaLabel ?: ($heading !== '' ? $heading : __('web::app.home.index.manasik-showcase'));
@endphp

@if (count($cards) > 0)
    <section
        class="web-supplications web-home-manasik-showcase"
        aria-label="{{ $sectionAria }}"
    >
        <div class="web-supplications__inner">
            @if ($heading !== '' || $subheading !== '')
                <header class="web-supplications__head">
                    @if ($heading !== '')
                        <h2 class="web-supplications__title">{{ $heading }}</h2>
                    @endif
                    @if ($subheading !== '')
                        <p class="web-supplications__sub">{{ $subheading }}</p>
                    @endif
                </header>
            @endif

            <div class="rituals-showcase">
                @foreach ($cards as $card)
                    @php
                        $num = str_pad((string) ((int) ($card['index'] ?? 0)), 2, '0', STR_PAD_LEFT);
                        $badge = trim((string) ($card['badge'] ?? ''));
                        $title = trim((string) ($card['title'] ?? ''));
                        $subtitle = trim((string) ($card['subtitle'] ?? ''));
                        $description = (string) ($card['description'] ?? '');
                        $chips = is_array($card['info_chips'] ?? null) ? $card['info_chips'] : [];
                        $duaText = trim((string) ($card['dua_text'] ?? ''));
                        $duaRef = trim((string) ($card['dua_reference'] ?? ''));
                    @endphp
                    <a
                        href="{{ $detailBaseUrl }}"
                        class="ritual-card"
                        aria-label="{{ $title }} — {{ __('web::app.home.manasik-showcase.details') }}"
                    >
                        <div class="card-header">
                            @if ($badge !== '')
                                <div class="card-badge">{{ $badge }}</div>
                            @endif
                            <div class="card-number" aria-hidden="true">
                                <span>{{ $num }}</span>
                            </div>
                            <h3 class="ritual-card__title">{{ $title }}</h3>
                            @if ($subtitle !== '')
                                <p class="ritual-card__subtitle">{{ $subtitle }}</p>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="ritual-card__main">
                                @if ($description !== '')
                                    <p class="ritual-description">{{ $description }}</p>
                                @endif

                                <div class="quick-info">
                                    @if (isset($chips[0]) && $chips[0] !== '')
                                        <span class="info-chip">{{ $chips[0] }}</span>
                                    @endif
                                </div>

                                @if ($duaText !== '')
                                    <div class="dua-preview">
                                        <div class="dua-text">{{ $duaText }}</div>
                                        @if ($duaRef !== '')
                                            <div class="dua-source">{{ $duaRef }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="web-maps-locations-page__card-buttons ritual-card__footer-buttons">
                                <span class="web-maps-locations-page__btn-details">{{ __('web::app.home.manasik-showcase.details') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($showMore && $moreUrl !== '')
                <div class="web-home-cta-below-grid">
                    <a href="{{ $moreUrl }}" class="web-maps-locations-page__btn-details">
                        {{ __('web::app.home.manasik-showcase.view_more') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif
