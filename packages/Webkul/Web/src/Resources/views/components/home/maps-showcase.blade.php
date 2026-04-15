@props([
    'options' => [],
    'sectionId' => 0,
    'ariaLabel' => null,
])

@php
    use Illuminate\Support\Facades\Schema;
    use Webkul\Manasik\Repositories\MapLocationRepository;

    $heading = trim((string) ($options['heading'] ?? ''));
    $subheading = trim((string) ($options['subheading'] ?? ''));
    $limit = (int) ($options['limit'] ?? 0);
    $limit = max(0, min(50, $limit));

    $linkShow = array_key_exists('link_show', $options)
        ? filter_var($options['link_show'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $linkLabel = trim((string) ($options['link_label'] ?? ''));
    if ($linkLabel === '') {
        $linkLabel = __('web::app.home.maps-showcase.link-default');
    }

    $cards = [];
    $cardDetails = [];

    if (Schema::hasTable('manasik_map_locations')) {
        $fromDb = app(MapLocationRepository::class)->getActiveCardsForLocale();
        if ($fromDb->isNotEmpty()) {
            $cards = $limit > 0 ? $fromDb->take($limit)->all() : $fromDb->all();
        }
    }

    foreach ($cards as $row) {
        if (! is_array($row) || empty($row['slug'])) {
            continue;
        }
        $slug = (string) $row['slug'];
        $cardDetails[$slug] = (string) ($row['detail_alert'] ?? '');
    }

    $idSuffix = '-home-'.(int) $sectionId;
    $mapsUrl = \Illuminate\Support\Facades\Route::has('web.maps.index')
        ? route('web.maps.index')
        : '#';
    $sectionAria = $ariaLabel ?: ($heading !== '' ? $heading : __('web::app.home.index.maps-showcase'));
@endphp

@if (count($cards) > 0)
    <section
        class="web-supplications web-home-maps-showcase"
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

            <div class="web-maps-locations-page">
                <div class="web-maps-locations-page__container">
                    @include('web::maps.partials.location-cards', [
                        'cards' => $cards,
                        'cardDetails' => $cardDetails,
                        'idSuffix' => $idSuffix,
                    ])

                    @if ($linkShow && $mapsUrl !== '#')
                        <div class="web-home-cta-below-grid">
                            <a
                                href="{{ $mapsUrl }}"
                                class="web-maps-locations-page__btn-details"
                            >
                                {{ $linkLabel }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
