@props(['opts' => []])

@php
    $o = is_array($opts) ? $opts : [];
    $ufId = 'webuf-' . \Illuminate\Support\Str::random(8);
    $isRtl = in_array(app()->getLocale(), ['ar', 'fa'], true);
    $chev = $isRtl ? 'fa-chevron-left' : 'fa-chevron-right';

    $brand = array_merge([
        'icon' => 'fas fa-kaaba', 'title' => '', 'description' => '', 'trust' => '',
    ], $o['brand'] ?? []);

    $social = is_array($o['social'] ?? null) ? $o['social'] : [];
    $colExplore = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_explore'] ?? []);
    $colSupport = array_merge(['title' => '', 'show_chevron' => true, 'links' => []], $o['col_support'] ?? []);
    $contact = array_merge(['title' => '', 'items' => []], $o['contact'] ?? []);
    $subscribe = array_merge([
        'title' => '', 'placeholder' => '', 'privacy' => '',
        'success_msg' => '', 'invalid_msg' => '',
    ], $o['subscribe'] ?? []);
    $bottom = array_merge(['copyright' => '', 'mini_nav_label' => '', 'links' => []], $o['bottom'] ?? []);
    $effects = array_merge(['back_to_top' => true], $o['effects'] ?? []);

    $copyrightRaw = trim((string) ($bottom['copyright'] ?? ''));
    $copyrightLine = $copyrightRaw !== ''
        ? str_replace(':year', (string) date('Y'), $copyrightRaw)
        : __('web::app.manasik_footer.copyright', ['year' => date('Y')]);

    $okMsg = trim((string) ($subscribe['success_msg'] ?? '')) ?: __('web::app.manasik_footer.nl_success');
    $badMsg = trim((string) ($subscribe['invalid_msg'] ?? '')) ?: __('web::app.manasik_footer.nl_invalid');
    $miniNavAria = trim((string) ($bottom['mini_nav_label'] ?? '')) ?: __('web::app.manasik_footer.mini_nav');
@endphp

@pushOnce('styles', 'web-footer-fontawesome')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
@endPushOnce

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
                        <i class="{{ $brand['icon'] ?: 'fas fa-kaaba' }}" aria-hidden="true"></i>
                        <span>{{ $brand['title'] }}</span>
                    </div>
                    @if (! empty($brand['description']))
                        <p class="web-uf__desc">{{ $brand['description'] }}</p>
                    @endif
                    @if (! empty($brand['trust']))
                        <div class="web-uf__trust">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            <span>{{ $brand['trust'] }}</span>
                        </div>
                    @endif
                    @if ($social !== [])
                        <div class="web-uf__social">
                            @foreach ($social as $s)
                                @php
                                    $sUrl = (string) ($s['url'] ?? '#');
                                    $sIcon = (string) ($s['icon'] ?? 'fab fa-link');
                                    $sAria = trim((string) ($s['aria_label'] ?? ''));
                                @endphp
                                <a href="{{ $sUrl ?: '#' }}" @if($sAria !== '') aria-label="{{ $sAria }}" @endif>
                                    <i class="{{ $sIcon }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    @if (! empty($colExplore['title']))
                        <h3 class="web-uf__col-title">{{ $colExplore['title'] }}</h3>
                    @endif
                    <ul class="web-uf__links">
                        @foreach ($colExplore['links'] ?? [] as $row)
                            <li>
                                <a href="{{ $row['url'] ?? '#' }}">
                                    @if (! empty($colExplore['show_chevron']))
                                        <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                    @endif
                                    {{ $row['label'] ?? '' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    @if (! empty($colSupport['title']))
                        <h3 class="web-uf__col-title">{{ $colSupport['title'] }}</h3>
                    @endif
                    <ul class="web-uf__links">
                        @foreach ($colSupport['links'] ?? [] as $row)
                            <li>
                                <a href="{{ $row['url'] ?? '#' }}">
                                    @if (! empty($colSupport['show_chevron']))
                                        <i class="fas {{ $chev }}" aria-hidden="true"></i>
                                    @endif
                                    {{ $row['label'] ?? '' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    @if (! empty($contact['title']))
                        <h3 class="web-uf__col-title">{{ $contact['title'] }}</h3>
                    @endif
                    <div class="web-uf__contact">
                        @foreach ($contact['items'] ?? [] as $it)
                            <div class="web-uf__contact-item">
                                @if (! empty($it['icon']))
                                    <i class="{{ $it['icon'] }}" aria-hidden="true"></i>
                                @endif
                                <span>{{ $it['text'] ?? '' }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="web-uf__subscribe-block">
                        @if (! empty($subscribe['title']))
                            <h3 class="web-uf__col-title web-uf__col-title--sub">{{ $subscribe['title'] }}</h3>
                        @endif
                        <div class="web-uf__nl">
                            <input
                                type="email"
                                id="{{ $ufId }}-email"
                                class="web-uf__nl-input"
                                placeholder="{{ $subscribe['placeholder'] ?: __('web::app.manasik_footer.nl_placeholder') }}"
                                autocomplete="email"
                            >
                            <button
                                type="button"
                                class="web-uf__nl-btn web-uf-nl-trigger"
                                data-msg-ok="{{ e($okMsg) }}"
                                data-msg-bad="{{ e($badMsg) }}"
                                aria-label="{{ $subscribe['title'] ?: __('web::app.manasik_footer.titles.subscribe') }}"
                            >
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                            </button>
                        </div>
                        @if (! empty($subscribe['privacy']))
                            <p class="web-uf__nl-note">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                {{ $subscribe['privacy'] }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="web-uf__bar">
                <div class="web-uf__copyright">
                    <i class="far fa-copyright" aria-hidden="true"></i>
                    {{ $copyrightLine }}
                </div>
                <nav class="web-uf__mini" aria-label="{{ e($miniNavAria) }}">
                    @foreach ($bottom['links'] ?? [] as $m)
                        <a href="{{ $m['url'] ?? '#' }}">{{ $m['label'] ?? '' }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    @if (! empty($effects['back_to_top']))
        <button
            type="button"
            class="web-uf__gotop"
            data-web-uf-gotop
            aria-label="{{ __('web::app.manasik_footer.back_top') }}"
        >
            <i class="fas fa-arrow-up" aria-hidden="true"></i>
        </button>
    @endif
</footer>
