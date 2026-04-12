@push('meta')
    <meta name="description" content="{{ __('web::app.maps.meta_description') }}" />
@endpush

<x-web::layouts :title="$pageTitle">
    <x-web::layouts.inner-page-hero page-key="maps" />

    <div class="web-maps-locations-page">
        <div class="web-maps-locations-page__container">
            @if (count($cards) === 0)
                <div
                    class="web-page-empty"
                    role="status"
                    aria-live="polite"
                >
                    <h2 class="web-page-empty__title">
                        {{ __('web::app.maps.empty_title') }}
                    </h2>
                    <div class="web-page-empty__gold-line" aria-hidden="true"></div>
                    <p class="web-page-empty__text">
                        {{ __('web::app.maps.empty_hint') }}
                    </p>
                    <a
                        href="{{ route('web.home.index') }}"
                        class="web-page-empty__btn"
                    >
                        {{ __('web::app.maps.empty_btn_home') }}
                    </a>
                </div>
            @else
                @include('web::maps.partials.location-cards', [
                    'cards' => $cards,
                    'cardDetails' => $cardDetails,
                    'idSuffix' => '',
                ])
            @endif
        </div>
    </div>
</x-web::layouts>
