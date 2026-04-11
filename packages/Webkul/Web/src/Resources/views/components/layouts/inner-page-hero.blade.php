@props([
    'overrides' => [],
    'pageKey' => null,
])

@php
    use Illuminate\Support\Facades\Schema;
    use Webkul\Web\Repositories\WebThemeCustomizationRepository;
    use Webkul\Web\Support\InnerPageHeroOptions;
    use Webkul\Web\Support\WebHeaderPrimaryTabs;

    $themeCode = config('web.storefront_theme_code', 'web');
    $innerRow = null;
    $headerRow = null;

    if (Schema::hasTable('shop_theme_customizations')) {
        $repo = app(WebThemeCustomizationRepository::class);
        $innerRow = $repo->getActiveInnerPageHero($themeCode);
        $headerRow = $repo->getActiveWebHeader($themeCode);
    }

    $fromDb = ($innerRow && is_array($innerRow->options)) ? $innerRow->options : [];
    $headerOpts = ($headerRow && is_array($headerRow->options)) ? $headerRow->options : [];
    $ov = is_array($overrides) ? $overrides : [];

    $resolvedPageKey = $pageKey !== null && trim((string) $pageKey) !== ''
        ? trim((string) $pageKey)
        : (string) (WebHeaderPrimaryTabs::pageKeyForRouteName(request()->route()?->getName()) ?? '');

    $h = InnerPageHeroOptions::resolveForPage($fromDb, $resolvedPageKey, $headerOpts, $ov);

    $visible = filter_var($h['visible'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $badgeShow = filter_var($h['badge_show'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $badgeText = trim((string) ($h['badge_text'] ?? ''));
    $badgeIcon = trim((string) ($h['badge_icon'] ?? ''));
    $title = trim((string) ($h['title'] ?? ''));
    $description = trim((string) ($h['description'] ?? ''));
    $breadcrumbRaw = is_array($h['breadcrumb'] ?? null) ? $h['breadcrumb'] : [];
    $breadcrumb = [];

    foreach ($breadcrumbRaw as $crumb) {
        if (! is_array($crumb)) {
            continue;
        }

        $cl = trim((string) ($crumb['label'] ?? ''));
        $cu = trim((string) ($crumb['url'] ?? ''));

        if ($cl === '' && $cu === '') {
            continue;
        }

        $breadcrumb[] = ['label' => $cl, 'url' => $cu];
    }

    $primaryShow = filter_var($h['primary_show'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $primaryLabel = trim((string) ($h['primary_label'] ?? ''));
    $primaryUrl = trim((string) ($h['primary_url'] ?? ''));
    $primaryIcon = trim((string) ($h['primary_icon'] ?? ''));

    $secondaryShow = filter_var($h['secondary_show'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $secondaryLabel = trim((string) ($h['secondary_label'] ?? ''));
    $secondaryUrl = trim((string) ($h['secondary_url'] ?? ''));
    $secondaryIcon = trim((string) ($h['secondary_icon'] ?? ''));

    $gFrom = (string) ($h['gradient_from'] ?? '#0d2a1a');
    $gMid = (string) ($h['gradient_mid'] ?? '#1a3a2a');
    $gTo = (string) ($h['gradient_to'] ?? '#0d2a1a');
    $gold = (string) ($h['gold'] ?? '#d4af37');
    $waveFill = (string) ($h['wave_fill'] ?? '#fefaf5');

    $isRtl = in_array(strtolower(app()->getLocale()), ['ar', 'fa', 'he', 'ur', 'ku', 'dv'], true);
    $sepIcon = $isRtl ? 'fa-chevron-left' : 'fa-chevron-right';

    $hasBadge = $badgeShow && ($badgeText !== '' || $badgeIcon !== '');
    $hasPrimary = $primaryShow && $primaryLabel !== '';
    $hasSecondary = $secondaryShow && $secondaryLabel !== '';
    $hasBreadcrumb = $breadcrumb !== [];

    $showHero = $visible && ($title !== '' || $description !== '' || $hasBadge || $hasBreadcrumb || $hasPrimary || $hasSecondary);

    if (request()->routeIs('web.home.index')) {
        $showHero = false;
    }
@endphp

@if ($showHero)
    <section
        class="web-inner-hero"
        style="--web-inner-hero-from: {{ $gFrom }}; --web-inner-hero-mid: {{ $gMid }}; --web-inner-hero-to: {{ $gTo }}; --web-inner-hero-gold: {{ $gold }}; --web-inner-hero-wave: {{ $waveFill }};"
        aria-labelledby="web-inner-hero-title"
    >
        <div class="web-inner-hero__container">
            @if ($hasBadge)
                <div class="web-inner-hero__badge">
                    @if ($badgeIcon !== '')
                        <i class="{{ $badgeIcon }}" aria-hidden="true"></i>
                    @endif
                    @if ($badgeText !== '')
                        <span>{{ $badgeText }}</span>
                    @endif
                </div>
            @endif

            @if ($hasBreadcrumb)
                <nav class="web-inner-hero__breadcrumb" aria-label="{{ __('web::app.inner_hero.breadcrumb_aria') }}">
                    @foreach ($breadcrumb as $i => $crumb)
                        @if ($i > 0)
                            <i class="fas {{ $sepIcon }} web-inner-hero__breadcrumb-sep" aria-hidden="true"></i>
                        @endif
                        @if ($crumb['url'] !== '')
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            <span>{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if ($title !== '')
                <h1 id="web-inner-hero-title" class="web-inner-hero__title">{{ $title }}</h1>
            @endif

            @if ($description !== '')
                <p class="web-inner-hero__description">{{ $description }}</p>
            @endif

            @if ($hasPrimary || $hasSecondary)
                <div class="web-inner-hero__buttons">
                    @if ($hasPrimary)
                        @if ($primaryUrl !== '')
                            <a href="{{ $primaryUrl }}" class="web-inner-hero__btn web-inner-hero__btn--primary">
                                @if ($primaryIcon !== '')
                                    <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                @endif
                                {{ $primaryLabel }}
                            </a>
                        @else
                            <button type="button" class="web-inner-hero__btn web-inner-hero__btn--primary">
                                @if ($primaryIcon !== '')
                                    <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                @endif
                                {{ $primaryLabel }}
                            </button>
                        @endif
                    @endif

                    @if ($hasSecondary)
                        @if ($secondaryUrl !== '')
                            <a href="{{ $secondaryUrl }}" class="web-inner-hero__btn web-inner-hero__btn--outline">
                                @if ($secondaryIcon !== '')
                                    <i class="{{ $secondaryIcon }}" aria-hidden="true"></i>
                                @endif
                                {{ $secondaryLabel }}
                            </a>
                        @else
                            <button type="button" class="web-inner-hero__btn web-inner-hero__btn--outline">
                                @if ($secondaryIcon !== '')
                                    <i class="{{ $secondaryIcon }}" aria-hidden="true"></i>
                                @endif
                                {{ $secondaryLabel }}
                            </button>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <div class="web-inner-hero__wave" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    fill="{{ $waveFill }}"
                    d="M0,64L80,69C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"
                />
            </svg>
        </div>
    </section>
@endif
