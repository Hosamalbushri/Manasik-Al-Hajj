@props([
    'options' => [],
    'sectionId' => 0,
    'ariaLabel' => null,
])

@php
    use Webkul\Web\Support\WebStoreBandColors;

    $heading = mb_substr(trim((string) ($options['heading'] ?? '')), 0, 191);
    $description = mb_substr(trim(strip_tags((string) ($options['description'] ?? ''))), 0, 500);
    $locationLabel = mb_substr(trim((string) ($options['location_label'] ?? '')), 0, 191);
    if ($locationLabel === '') {
        $locationLabel = __('web::app.home.prayer-times.default_location');
    }
    $apiUrl = trim((string) ($options['api_url'] ?? ''));
    if ($apiUrl !== '' && (! preg_match('#^https?://#i', $apiUrl) || strlen($apiUrl) > 2000)) {
        $apiUrl = '';
    }
    $city = trim((string) ($options['city'] ?? 'Makkah'));
    $country = trim((string) ($options['country'] ?? 'Saudi Arabia'));
    $method = max(0, min(15, (int) ($options['method'] ?? 2)));
    $autoplayMs = (int) ($options['autoplay_ms'] ?? 4000);
    $autoplayMs = max(1000, min(60000, $autoplayMs));
    $hour12Opt = $options['hour12'] ?? null;
    if (is_array($hour12Opt)) {
        $hour12Opt = end($hour12Opt);
    }
    $hour12 = $hour12Opt === null || $hour12Opt === ''
        ? true
        : filter_var($hour12Opt, FILTER_VALIDATE_BOOLEAN);

    $slides = [
        ['api' => 'Fajr', 'icon' => 'fas fa-star-and-crescent'],
        ['api' => 'Sunrise', 'icon' => 'fas fa-sun'],
        ['api' => 'Dhuhr', 'icon' => 'fas fa-sun'],
        ['api' => 'Asr', 'icon' => 'fas fa-cloud-sun'],
        ['api' => 'Maghrib', 'icon' => 'fas fa-moon'],
        ['api' => 'Isha', 'icon' => 'fas fa-star-of-life'],
    ];
    $slidePairs = array_chunk($slides, 2);

    $labelFor = static function (string $api): string {
        return match ($api) {
            'Fajr' => __('web::app.home.prayer-times.fajr'),
            'Sunrise' => __('web::app.home.prayer-times.sunrise'),
            'Dhuhr' => __('web::app.home.prayer-times.dhuhr'),
            'Asr' => __('web::app.home.prayer-times.asr'),
            'Maghrib' => __('web::app.home.prayer-times.maghrib'),
            'Isha' => __('web::app.home.prayer-times.isha'),
            default => $api,
        };
    };

    $prayerClientConfig = [
        'apiUrl' => $apiUrl,
        'city' => $city,
        'country' => $country,
        'method' => $method,
        'autoplayMs' => $autoplayMs,
        'hour12' => $hour12,
        'labels' => [
            'next' => __('web::app.home.prayer-times.next_label'),
            'updated' => __('web::app.home.prayer-times.updated_note'),
            'prayers' => [
                'Fajr' => $labelFor('Fajr'),
                'Sunrise' => $labelFor('Sunrise'),
                'Dhuhr' => $labelFor('Dhuhr'),
                'Asr' => $labelFor('Asr'),
                'Maghrib' => $labelFor('Maghrib'),
                'Isha' => $labelFor('Isha'),
            ],
            'clock' => [
                'am' => __('web::app.home.prayer-times.am'),
                'pm' => __('web::app.home.prayer-times.pm'),
            ],
        ],
    ];

    $sectionAriaFromDescription = '';
    if ($description !== '') {
        $sectionAriaFromDescription = mb_strlen($description) <= 120
            ? $description
            : rtrim(mb_substr($description, 0, 119)) . '…';
    }
    $sectionAria = $ariaLabel ?: ($heading !== ''
        ? $heading
        : ($sectionAriaFromDescription !== ''
            ? $sectionAriaFromDescription
            : __('web::app.home.index.prayer-times')));

    $cfgBand = config('web.band_background', []);
    $storeGrad = WebStoreBandColors::innerHeroStoreDefaults();
    $prayerColorMap = [
        'band_gradient_from' => ['var' => '--web-prayer-band-from', 'sk' => 'from'],
        'band_gradient_mid'  => ['var' => '--web-prayer-band-mid', 'sk' => 'mid'],
        'band_gradient_to'   => ['var' => '--web-prayer-band-to', 'sk' => 'to'],
        'accent_gold'        => ['var' => '--web-prayer-accent-gold', 'kind' => 'gold'],
    ];
    $prayerStylePieces = [];
    foreach ($prayerColorMap as $optKey => $spec) {
        $hex = strtoupper(trim((string) ($options[$optKey] ?? '')));
        if ($hex === '' || ! preg_match('/^#[0-9A-F]{6}$/', $hex)) {
            if (($spec['kind'] ?? '') === 'gold') {
                $hex = strtoupper(trim((string) ($cfgBand['gold'] ?? '')));
            } else {
                $hex = $storeGrad[$spec['sk']];
            }
        }
        if ($hex !== '' && preg_match('/^#[0-9A-F]{6}$/', $hex)) {
            $prayerStylePieces[] = $spec['var'].': '.$hex;
        }
    }
    $prayerInlineStyle = $prayerStylePieces === [] ? null : implode('; ', $prayerStylePieces);
@endphp

<section
    class="web-prayer-times"
    data-web-prayer-times
    aria-label="{{ $sectionAria }}"
    @if ($prayerInlineStyle !== null) style="{{ $prayerInlineStyle }}" @endif
>
    <script
        type="application/json"
        class="web-prayer-times__config"
    >@json($prayerClientConfig)</script>

    <div class="web-prayer-times__inner">
        @if ($heading !== '' || $description !== '')
            <header class="web-prayer-times__section-head">
                @if ($heading !== '')
                    <h2 class="web-prayer-times__title">{{ $heading }}</h2>
                @endif
                @if ($description !== '')
                    <p class="web-prayer-times__description">{!! nl2br(e($description)) !!}</p>
                @endif
            </header>
        @endif

        <div class="web-prayer-times__location">
            <span>
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                {{ $locationLabel }}
            </span>
        </div>

        <div class="web-prayer-times__swiper-wrap">
                <div class="swiper web-prayer-times__swiper">
                    <div class="swiper-wrapper">
                        @foreach ($slidePairs as $pair)
                            <div class="swiper-slide">
                                <article class="web-prayer-times__slide web-prayer-times__slide--pair">
                                    <div class="web-prayer-times__pair-grid">
                                        @foreach ($pair as $item)
                                            <div class="web-prayer-times__pair-cell">
                                                <div class="web-prayer-times__slide-stack">
                                                    <div class="web-prayer-times__slide-name-row">
                                                        <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                                                        <span class="web-prayer-times__slide-name">{{ $labelFor($item['api']) }}</span>
                                                    </div>
                                                    <div
                                                        class="web-prayer-times__slide-time"
                                                        data-prayer-time="{{ $item['api'] }}"
                                                    >
                                                        <span dir="ltr" class="web-prayer-times__clock" translate="no">--:--</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Outside .swiper so overflow:hidden does not clip bullets; Swiper accepts external el --}}
                <div
                    class="swiper-pagination web-prayer-times__pagination"
                    aria-label="{{ __('web::app.home.prayer-times.pagination_label') }}"
                ></div>
        </div>

        <div class="web-prayer-times__next">
            <div class="web-prayer-times__next-main">
                <i class="fas fa-bell web-prayer-times__next-bell" aria-hidden="true"></i>
                <span class="web-prayer-times__next-label">{{ __('web::app.home.prayer-times.next_label') }}</span>
                <span class="web-prayer-times__next-name" data-prayer-next-name>--</span>
            </div>
            <time class="web-prayer-times__next-time" data-prayer-next-time datetime="">
                <span dir="ltr" class="web-prayer-times__clock" translate="no">--:--</span>
            </time>
        </div>

        <div class="web-prayer-times__dates">
            <div class="web-prayer-times__hijri" data-prayer-hijri>--</div>
            <div class="web-prayer-times__gregorian" data-prayer-gregorian>--</div>
        </div>

        <p class="web-prayer-times__footnote">
            <i class="fas fa-sync-alt" aria-hidden="true"></i>
            {{ __('web::app.home.prayer-times.updated_note') }}
        </p>
    </div>
</section>
