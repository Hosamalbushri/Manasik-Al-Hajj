@props([
    'options' => [],
    'ariaLabel' => null,
])

@php
    $slides = is_array($options['slides'] ?? null) ? $options['slides'] : [];
@endphp

@if (count($slides))
    <section class="web-hero-slider" @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
        <div class="swiper webHeroSwiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                @php
                    $imagePath = (string) ($slide['image'] ?? '');
                    $imageUrl = $imagePath;

                    if (
                        $imagePath !== ''
                        && ! str_starts_with($imagePath, 'http://')
                        && ! str_starts_with($imagePath, 'https://')
                        && ! str_starts_with($imagePath, '//')
                        && ! str_starts_with($imagePath, 'data:')
                    ) {
                        $imageUrl = \Illuminate\Support\Facades\Storage::url(ltrim($imagePath, '/'));
                    }

                    $badgeText = trim((string) ($slide['badge'] ?? ''));
                    $badgeIcon = trim((string) ($slide['badge_icon'] ?? ''));

                    $primaryUrl = trim((string) data_get($slide, 'primary.url', ''));
                    $primaryLabel = trim((string) data_get($slide, 'primary.label', ''));
                    $primaryIcon = trim((string) data_get($slide, 'primary.icon', ''));

                    $secondaryUrl = trim((string) data_get($slide, 'secondary.url', ''));
                    $secondaryLabel = trim((string) data_get($slide, 'secondary.label', ''));
                    $secondaryIcon = trim((string) data_get($slide, 'secondary.icon', ''));
                @endphp

                    <div class="swiper-slide">
                        <div class="web-slide-bg">
                            <img class="web-slide-bg-img" src="{{ $imageUrl }}" alt="{{ $slide['title'] }}">
                        </div>
                        <div class="web-slide-overlay" aria-hidden="true"></div>
                        <div class="web-slide-content">
                            @if ($badgeText !== '')
                                <div class="web-slide-badge">
                                    @if ($badgeIcon !== '')
                                        <i class="{{ $badgeIcon }}"></i>
                                    @endif

                                    {{ $badgeText }}
                                </div>
                            @endif

                            <h1 class="web-slide-title">{{ $slide['title'] }}</h1>

                            <p class="web-slide-description">{{ $slide['description'] }}</p>

                            @if ($primaryUrl !== '' || $secondaryUrl !== '')
                                <div class="web-slide-buttons">
                                    @if ($primaryUrl !== '')
                                        <a href="{{ $primaryUrl }}" class="web-btn-primary-slide">
                                            @if ($primaryIcon !== '')
                                                <i class="{{ $primaryIcon }}"></i>
                                            @endif

                                            {{ $primaryLabel !== '' ? $primaryLabel : 'Action' }}
                                        </a>
                                    @endif

                                    @if ($secondaryUrl !== '')
                                        <a href="{{ $secondaryUrl }}" class="web-btn-outline-slide">
                                            @if ($secondaryIcon !== '')
                                                <i class="{{ $secondaryIcon }}"></i>
                                            @endif

                                            {{ $secondaryLabel !== '' ? $secondaryLabel : 'Action' }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="web-slide-stats">
                                @foreach ($slide['stats'] as $stat)
                                    <div class="web-stat-item">
                                        <div class="web-stat-number">{{ $stat['number'] }}</div>
                                        <div class="web-stat-label">{{ $stat['label'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-next web-swiper-next"></div>
            <div class="swiper-button-prev web-swiper-prev"></div>
            <div class="swiper-pagination web-swiper-pagination"></div>
        </div>
    </section>
@endif

