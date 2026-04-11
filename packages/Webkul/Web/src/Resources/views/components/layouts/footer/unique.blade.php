@php
    $ufId = 'webuf-' . \Illuminate\Support\Str::random(8);
    $isRtl = in_array(app()->getLocale(), ['ar', 'fa'], true);
    $chev = $isRtl ? 'fa-chevron-left' : 'fa-chevron-right';

    $homeUrl = \Illuminate\Support\Facades\Route::has('web.home.index') ? route('web.home.index') : '#';
    $mapsUrl = \Illuminate\Support\Facades\Route::has('web.maps.index') ? route('web.maps.index') : '/maps';

    $exploreLinks = [
        ['label' => __('web::app.manasik_footer.links.home'), 'url' => $homeUrl],
        ['label' => __('web::app.manasik_footer.links.hajj'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.umrah'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.calendar'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.maps'), 'url' => $mapsUrl],
    ];

    $supportLinks = [
        ['label' => __('web::app.manasik_footer.links.help'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.faq'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.terms'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.privacy'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.links.contact'), 'url' => '#'],
    ];

    $miniLinks = [
        ['label' => __('web::app.manasik_footer.mini.sitemap'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.mini.accessibility'), 'url' => '#'],
        ['label' => __('web::app.manasik_footer.mini.developers'), 'url' => '#'],
    ];
@endphp

@pushOnce('styles', 'web-footer-fontawesome')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
@endpushOnce

<footer
    class="web-uf"
    id="{{ $ufId }}"
    role="contentinfo"
    data-web-uf-footer="1"
>
    <div class="web-uf__bg">
        <div class="web-uf__container">
            <div class="web-uf__grid">
                <div class="web-uf__brand">
                    <div class="web-uf__logo-pill">
                        <i class="fas fa-kaaba" aria-hidden="true"></i>
                        <span>{{ __('web::app.manasik_footer.brand') }}</span>
                    </div>
                    <p class="web-uf__desc">{{ __('web::app.manasik_footer.description') }}</p>
                    <div class="web-uf__trust">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <span>{{ __('web::app.manasik_footer.trust') }}</span>
                    </div>
                    <div class="web-uf__social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="web-uf__col-title">{{ __('web::app.manasik_footer.titles.explore') }}</h3>
                    <ul class="web-uf__links">
                        @foreach ($exploreLinks as $row)
                            <li>
                                <a href="{{ $row['url'] }}">
                                    <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                    {{ $row['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="web-uf__col-title">{{ __('web::app.manasik_footer.titles.support') }}</h3>
                    <ul class="web-uf__links">
                        @foreach ($supportLinks as $row)
                            <li>
                                <a href="{{ $row['url'] }}">
                                    <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                    {{ $row['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="web-uf__col-title">{{ __('web::app.manasik_footer.titles.contact') }}</h3>
                    <div class="web-uf__contact">
                        <div class="web-uf__contact-item">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span>{{ __('web::app.manasik_footer.contact.address') }}</span>
                        </div>
                        <div class="web-uf__contact-item">
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                            <span>{{ __('web::app.manasik_footer.contact.phone') }}</span>
                        </div>
                        <div class="web-uf__contact-item">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <span>{{ __('web::app.manasik_footer.contact.email') }}</span>
                        </div>
                    </div>
                    <div class="web-uf__subscribe-block">
                        <h3 class="web-uf__col-title web-uf__col-title--sub">{{ __('web::app.manasik_footer.titles.subscribe') }}</h3>
                        <div class="web-uf__nl">
                            <input
                                type="email"
                                id="{{ $ufId }}-email"
                                class="web-uf__nl-input"
                                placeholder="{{ __('web::app.manasik_footer.nl_placeholder') }}"
                                autocomplete="email"
                            >
                            <button
                                type="button"
                                class="web-uf__nl-btn web-uf-nl-trigger"
                                data-msg-ok="{{ e(__('web::app.manasik_footer.nl_success')) }}"
                                data-msg-bad="{{ e(__('web::app.manasik_footer.nl_invalid')) }}"
                                aria-label="{{ __('web::app.manasik_footer.titles.subscribe') }}"
                            >
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p class="web-uf__nl-note">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            {{ __('web::app.manasik_footer.nl_privacy') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="web-uf__bar">
                <div class="web-uf__copyright">
                    <i class="far fa-copyright" aria-hidden="true"></i>
                    {{ __('web::app.manasik_footer.copyright', ['year' => date('Y')]) }}
                </div>
                <nav class="web-uf__mini" aria-label="{{ __('web::app.manasik_footer.mini_nav') }}">
                    @foreach ($miniLinks as $m)
                        <a href="{{ $m['url'] }}">{{ $m['label'] }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    <button
        type="button"
        class="web-uf__gotop"
        data-web-uf-gotop
        aria-label="{{ __('web::app.manasik_footer.back_top') }}"
    >
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>
</footer>
