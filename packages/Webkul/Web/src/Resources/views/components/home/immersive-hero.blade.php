@props([
    'ariaLabel' => null,
])

@php
    $slides = [
        [
            'badge_icon' => 'fas fa-kaaba',
            'badge' => '1446هـ - رحلة العمر',
            'title' => 'حج مبرور وسعي مشكور',
            'description' => 'نقدم لك أسهل الطرق لأداء مناسك الحج والعمرة بكل يسر، مع خطط مخصصة ودعم على مدار الساعة.',
            'image' => 'https://images.pexels.com/photos/4741199/pexels-photo-4741199.jpeg?auto=compress&cs=tinysrgb&w=1600',
            'primary' => ['label' => 'خطط رحلتك', 'icon' => 'fas fa-calendar-check', 'url' => '#'],
            'secondary' => ['label' => 'شاهد المناسك', 'icon' => 'fas fa-play-circle', 'url' => '#'],
            'stats' => [
                ['number' => '+50K', 'label' => 'حاج سنويا'],
                ['number' => '5 نجوم', 'label' => 'خدمات متميزة'],
                ['number' => '24/7', 'label' => 'دعم مباشر'],
            ],
        ],
        [
            'badge_icon' => 'fas fa-mosque',
            'badge' => 'نور الإيمان',
            'title' => 'عمرة مقبولة وذنب مغفور',
            'description' => 'استمتع بتجربة روحانية فريدة مع باقات العمرة الفاخرة، وتوجيه وإرشاد من خبراء الرحلات الإسلامية.',
            'image' => 'https://images.pexels.com/photos/3773645/pexels-photo-3773645.jpeg?auto=compress&cs=tinysrgb&w=1600',
            'primary' => ['label' => 'احجز العمرة', 'icon' => 'fas fa-ticket-alt', 'url' => '#'],
            'secondary' => ['label' => 'دليل المعتمر', 'icon' => 'fas fa-book-quran', 'url' => '#'],
            'stats' => [
                ['number' => '+100K', 'label' => 'معتمر'],
                ['number' => '4.9', 'label' => 'تقييم'],
                ['number' => 'VIP', 'label' => 'خدمات حصرية'],
            ],
        ],
        [
            'badge_icon' => 'fas fa-microchip',
            'badge' => 'تقنية ذكية',
            'title' => 'خطط لمناسكك بخطوات ذكية',
            'description' => 'تطبيق مناسك الحج يرشدك خطوة بخطوة، خرائط تفاعلية، تنبيهات المشاعر، وترجمة فورية بأكثر من 10 لغات.',
            'image' => 'https://images.pexels.com/photos/5409232/pexels-photo-5409232.jpeg?auto=compress&cs=tinysrgb&w=1600',
            'primary' => ['label' => 'حمل التطبيق', 'icon' => 'fab fa-android', 'url' => '#'],
            'secondary' => ['label' => 'خدمات متعددة اللغات', 'icon' => 'fas fa-language', 'url' => '#'],
            'stats' => [
                ['number' => '10+', 'label' => 'لغات'],
                ['number' => '24', 'label' => 'ساعة دعم'],
                ['number' => 'خريطة', 'label' => 'تفاعلية'],
            ],
        ],
    ];
@endphp

<section class="web-hero-slider" @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
    <div class="swiper webHeroSwiper">
        <div class="swiper-wrapper">
            @foreach ($slides as $slide)
                <div class="swiper-slide">
                    <div class="web-slide-bg">
                        <img class="web-slide-bg-img" src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                    </div>
                    <div class="web-slide-overlay"></div>
                    <div class="web-slide-content">
                        <div class="web-slide-badge">
                            <i class="{{ $slide['badge_icon'] }}"></i> {{ $slide['badge'] }}
                        </div>

                        <h1 class="web-slide-title">{{ $slide['title'] }}</h1>

                        <p class="web-slide-description">{{ $slide['description'] }}</p>

                        <div class="web-slide-buttons">
                            <a href="{{ $slide['primary']['url'] }}" class="web-btn-primary-slide">
                                <i class="{{ $slide['primary']['icon'] }}"></i> {{ $slide['primary']['label'] }}
                            </a>
                            <a href="{{ $slide['secondary']['url'] }}" class="web-btn-outline-slide">
                                <i class="{{ $slide['secondary']['icon'] }}"></i> {{ $slide['secondary']['label'] }}
                            </a>
                        </div>

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

