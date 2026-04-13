@props([
    'options' => [],
    'ariaLabel' => null,
])

@php
    use Webkul\Manasik\Repositories\DuaRepository;

    $heading = trim((string) ($options['heading'] ?? ''));
    $subheading = trim((string) ($options['subheading'] ?? ''));
    $limit = (int) ($options['limit'] ?? 6);
    $limit = max(1, min(50, $limit));
    $showMore = array_key_exists('show_more', $options)
        ? filter_var($options['show_more'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $moreUrl = trim((string) ($options['more_url'] ?? ''));
    if ($moreUrl === '' && \Illuminate\Support\Facades\Route::has('web.adhkar.index')) {
        $moreUrl = route('web.adhkar.index');
    }

    $duas = app(DuaRepository::class)->getHomePreviewDuas($limit);
@endphp

@if (count($duas))
    @include('web::components.adhkar.card-actions-script')

    <section
        class="web-supplications"
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
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

            <div class="web-adhkar-ui">
                <div class="web-adhkar-grid">
                    @foreach ($duas as $card)
                        <article class="web-adhkar-card" data-dua-id="{{ (int) ($card['id'] ?? 0) }}">
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

                <div id="web-adhkar-toast" class="web-adhkar-toast" role="status" aria-live="polite"></div>
            </div>

            @if ($showMore && $moreUrl !== '')
                <div class="web-home-cta-below-grid">
                    <a
                        href="{{ $moreUrl }}"
                        class="web-maps-locations-page__btn-details"
                    >
                        {{ __('web::app.home.supplications.view_more') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif
