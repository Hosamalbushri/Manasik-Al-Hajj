@props([
    'options' => [],
    'ariaLabel' => null,
])

@php
    use Webkul\Web\Support\WebStoreBandColors;

    $visible = filter_var($options['visible'] ?? true, FILTER_VALIDATE_BOOLEAN);

    $bandDefaults = WebStoreBandColors::defaultTriple();
    $cfgBand = config('web.band_background', []);

    $gFrom = WebStoreBandColors::optionalHex(core()->getConfigData('general.store.web.section_divider_gradient_from'))
        ?? WebStoreBandColors::optionalHex($options['gradient_from'] ?? '')
        ?? $bandDefaults['from'];
    $gMid = WebStoreBandColors::optionalHex(core()->getConfigData('general.store.web.section_divider_gradient_mid'))
        ?? WebStoreBandColors::optionalHex($options['gradient_mid'] ?? '')
        ?? $bandDefaults['mid'];
    $gTo = WebStoreBandColors::optionalHex(core()->getConfigData('general.store.web.section_divider_gradient_to'))
        ?? WebStoreBandColors::optionalHex($options['gradient_to'] ?? '')
        ?? $bandDefaults['to'];
    $gold = WebStoreBandColors::optionalHex($options['gold'] ?? '')
        ?? WebStoreBandColors::optionalHex($cfgBand['gold'] ?? '')
        ?? '#D4AF37';
    $waveFill = WebStoreBandColors::optionalHex($options['wave_fill'] ?? '')
        ?? '#FEFAF5';

    $optHexOrNull = static function ($raw): ?string {
        $v = strtoupper(trim((string) $raw));

        return preg_match('/^#[0-9A-F]{6}$/', $v) ? $v : null;
    };
    $parchmentStyleParts = ['--sd-gold: '.$gold];
    foreach (
        [
            'parchment_start' => '--web-parchment-start',
            'parchment_mid'   => '--web-parchment-mid',
            'parchment_end'   => '--web-parchment-end',
        ] as $optKey => $cssVar
    ) {
        $hx = $optHexOrNull($options[$optKey] ?? '');
        if ($hx !== null) {
            $parchmentStyleParts[] = $cssVar.': '.$hx;
        }
    }
    $parchmentInlineStyle = implode('; ', $parchmentStyleParts).';';

    $variant = strtolower(trim((string) ($options['variant'] ?? 'inset_card')));
    $variantAllowed = ['inset_card', 'full_bleed', 'content_heading', 'parchment_card'];
    if (! in_array($variant, $variantAllowed, true)) {
        $variant = 'inset_card';
    }

    $badgeShow = filter_var($options['badge_show'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $badgeText = trim((string) ($options['badge_text'] ?? ''));
    $badgeIcon = trim((string) ($options['badge_icon'] ?? ''));
    $title = trim((string) ($options['title'] ?? ''));
    $description = trim((string) ($options['description'] ?? ''));

    $primaryShow = filter_var($options['primary_show'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $primaryLabel = trim((string) ($options['primary_label'] ?? ''));
    $primaryUrl = trim((string) ($options['primary_url'] ?? ''));
    $primaryIcon = trim((string) ($options['primary_icon'] ?? ''));

    $secondaryShow = filter_var($options['secondary_show'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $secondaryLabel = trim((string) ($options['secondary_label'] ?? ''));
    $secondaryUrl = trim((string) ($options['secondary_url'] ?? ''));
    $secondaryIcon = trim((string) ($options['secondary_icon'] ?? ''));

    $hasBadge = $badgeShow && ($badgeText !== '' || $badgeIcon !== '');
    $hasPrimary = $primaryShow && $primaryLabel !== '';
    $hasSecondary = $secondaryShow && $secondaryLabel !== '';
    $hasContent = $hasBadge || $title !== '' || $description !== '' || $hasPrimary || $hasSecondary;

    $sectionId = 'web-section-divider-'.substr(sha1(json_encode($options).(string) ($ariaLabel ?? '')), 0, 10);
    $ariaDefault = __('web::app.home.index.section-divider');
    $aria = $ariaLabel ?: ($title !== '' ? $title : $ariaDefault);
@endphp

@if ($visible)
    @switch ($variant)
        @case ('full_bleed')
            <div class="web-section-divider web-section-divider--fullbleed {{ $hasContent ? '' : 'web-section-divider--compact' }}">
                <section
                    class="web-inner-hero web-inner-hero--section-divider web-inner-hero--section-divider--fullbleed {{ $hasContent ? '' : 'web-inner-hero--band-only' }}"
                    style="--web-inner-hero-from: {{ $gFrom }}; --web-inner-hero-mid: {{ $gMid }}; --web-inner-hero-to: {{ $gTo }}; --web-inner-hero-gold: {{ $gold }}; --web-inner-hero-wave: {{ $waveFill }};"
                    aria-label="{{ $aria }}"
                    @if ($title !== '') aria-labelledby="{{ $sectionId }}-fb-title" @endif
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
                        @if ($title !== '')
                            <h2 id="{{ $sectionId }}-fb-title" class="web-inner-hero__title">{{ $title }}</h2>
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
            </div>

            @break

        @case ('content_heading')
            <section
                class="web-section-divider-heading {{ $hasContent ? '' : 'web-section-divider-heading--compact' }}"
                style="--sd-gold: {{ $gold }};"
                aria-label="{{ $aria }}"
                @if ($title !== '') aria-labelledby="{{ $sectionId }}-ch-title" @endif
            >
                <div class="web-section-divider-heading__inner">
                    @if ($hasBadge)
                        <div class="web-section-divider-heading__badge">
                            @if ($badgeIcon !== '')
                                <i class="{{ $badgeIcon }}" aria-hidden="true"></i>
                            @endif
                            @if ($badgeText !== '')
                                <span>{{ $badgeText }}</span>
                            @endif
                        </div>
                    @endif

                    @if ($title !== '' || $description !== '')
                        <header class="web-section-divider-heading__head">
                            @if ($title !== '')
                                <h2 id="{{ $sectionId }}-ch-title" class="web-section-divider-heading__title">{{ $title }}</h2>
                            @endif
                            @if ($description !== '')
                                <p class="web-section-divider-heading__sub">{{ $description }}</p>
                            @endif
                        </header>
                    @endif

                    @if ($hasPrimary || $hasSecondary)
                        <div class="web-section-divider-heading__actions">
                            @if ($hasPrimary)
                                @if ($primaryUrl !== '')
                                    <a href="{{ $primaryUrl }}" class="web-section-divider-heading__btn web-section-divider-heading__btn--primary">
                                        @if ($primaryIcon !== '')
                                            <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                        @endif
                                        {{ $primaryLabel }}
                                    </a>
                                @else
                                    <button type="button" class="web-section-divider-heading__btn web-section-divider-heading__btn--primary">
                                        @if ($primaryIcon !== '')
                                            <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                        @endif
                                        {{ $primaryLabel }}
                                    </button>
                                @endif
                            @endif
                            @if ($hasSecondary)
                                @if ($secondaryUrl !== '')
                                    <a href="{{ $secondaryUrl }}" class="web-section-divider-heading__btn web-section-divider-heading__btn--outline">
                                        @if ($secondaryIcon !== '')
                                            <i class="{{ $secondaryIcon }}" aria-hidden="true"></i>
                                        @endif
                                        {{ $secondaryLabel }}
                                    </a>
                                @else
                                    <button type="button" class="web-section-divider-heading__btn web-section-divider-heading__btn--outline">
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
            </section>

            @break

        @case ('parchment_card')
            <div class="web-section-divider web-section-divider--parchment-outer {{ $hasContent ? '' : 'web-section-divider--compact' }}">
                <div
                    class="web-section-divider-parchment {{ $hasContent ? '' : 'web-section-divider-parchment--compact' }}"
                    style="{{ $parchmentInlineStyle }}"
                >
                    <section
                        class="web-section-divider-parchment__section"
                        aria-label="{{ $aria }}"
                        @if ($title !== '') aria-labelledby="{{ $sectionId }}-pc-title" @endif
                    >
                        @if ($hasBadge)
                            <div class="web-section-divider-parchment__badge">
                                @if ($badgeIcon !== '')
                                    <i class="{{ $badgeIcon }}" aria-hidden="true"></i>
                                @endif
                                @if ($badgeText !== '')
                                    <span>{{ $badgeText }}</span>
                                @endif
                            </div>
                        @endif
                        @if ($title !== '')
                            <h2 id="{{ $sectionId }}-pc-title" class="web-section-divider-parchment__title">{{ $title }}</h2>
                        @endif
                        @if ($description !== '')
                            <p class="web-section-divider-parchment__text">{{ $description }}</p>
                        @endif
                        @if ($hasPrimary || $hasSecondary)
                            <div class="web-section-divider-parchment__actions">
                                @if ($hasPrimary)
                                    @if ($primaryUrl !== '')
                                        <a href="{{ $primaryUrl }}" class="web-section-divider-parchment__btn web-section-divider-parchment__btn--primary">
                                            @if ($primaryIcon !== '')
                                                <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $primaryLabel }}
                                        </a>
                                    @else
                                        <button type="button" class="web-section-divider-parchment__btn web-section-divider-parchment__btn--primary">
                                            @if ($primaryIcon !== '')
                                                <i class="{{ $primaryIcon }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $primaryLabel }}
                                        </button>
                                    @endif
                                @endif
                                @if ($hasSecondary)
                                    @if ($secondaryUrl !== '')
                                        <a href="{{ $secondaryUrl }}" class="web-section-divider-parchment__btn web-section-divider-parchment__btn--outline">
                                            @if ($secondaryIcon !== '')
                                                <i class="{{ $secondaryIcon }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $secondaryLabel }}
                                        </a>
                                    @else
                                        <button type="button" class="web-section-divider-parchment__btn web-section-divider-parchment__btn--outline">
                                            @if ($secondaryIcon !== '')
                                                <i class="{{ $secondaryIcon }}" aria-hidden="true"></i>
                                            @endif
                                            {{ $secondaryLabel }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            @break

        @case ('inset_card')
        @default
            <div class="web-section-divider {{ $hasContent ? '' : 'web-section-divider--compact' }}">
                <div class="web-section-divider__panel">
                    <section
                        class="web-inner-hero web-inner-hero--section-divider {{ $hasContent ? '' : 'web-inner-hero--band-only' }}"
                        style="--web-inner-hero-from: {{ $gFrom }}; --web-inner-hero-mid: {{ $gMid }}; --web-inner-hero-to: {{ $gTo }}; --web-inner-hero-gold: {{ $gold }}; --web-inner-hero-wave: {{ $waveFill }};"
                        aria-label="{{ $aria }}"
                        @if ($title !== '') aria-labelledby="{{ $sectionId }}-title" @endif
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
                            @if ($title !== '')
                                <h2 id="{{ $sectionId }}-title" class="web-inner-hero__title">{{ $title }}</h2>
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
                </div>
            </div>

            @break
    @endswitch
@endif
