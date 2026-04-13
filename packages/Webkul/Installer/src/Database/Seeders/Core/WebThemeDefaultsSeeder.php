<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Webkul\Installer\Support\WebThemeInstallAssets;
use Webkul\Web\Models\ThemeCustomization;

class WebThemeDefaultsSeeder extends Seeder
{
    /**
     * Brand greens for header + shop CSS variables (seeded on install).
     */
    private const SHOP_PRIMARY = '#165022';

    private const SHOP_ACCENT = '#2E8B3A';

    private const SHOP_ICON = '#A67C2A';

    private const GOLD = '#D4AF37';

    private const GOLD_SOFT = '#C9A227';

    /** Inner hero band + footer contrast (aligned with brand primary). */
    private const DEEP_FOREST = '#165022';

    private const INNER_HERO_MID = '#1f6b2c';

    /** Locales always seeded for web theme (bilingual content). */
    private const THEME_LOCALES = ['ar', 'en'];

    /**
     * Seed default web theme: core colors, home hero, header, inner hero, footer.
     *
     * @param  array  $parameters
     */
    public function run(array $parameters = []): void
    {
        if (! Schema::hasTable('shop_theme_customizations')) {
            return;
        }

        WebThemeInstallAssets::ensureMakaPublishedToPublicDisk();

        $themeCode = (string) config('web.storefront_theme_code', 'web');
        $defaultLocale = strtolower((string) ($parameters['default_store_locale'] ?? $parameters['locale'] ?? config('app.locale', 'en')));
        if (! in_array($defaultLocale, self::THEME_LOCALES, true)) {
            $defaultLocale = 'en';
        }

        $now = now();

        $this->seedCoreWebColors($now);
        $this->seedAdminPanelFooterLabel($now, $defaultLocale);

        $this->seedStorefrontHomeSections($themeCode, $defaultLocale, $now);

        $this->upsertByType(
            'web_header',
            'Web Header',
            100,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->headerOptionsForLocale('ar'),
                'en' => $this->headerOptionsForLocale('en'),
            ]),
            $now
        );
        $this->upsertByType(
            'inner_page_hero',
            'Inner pages hero',
            110,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->innerPageHeroOptionsForLocale('ar'),
                'en' => $this->innerPageHeroOptionsForLocale('en'),
            ]),
            $now
        );
        $this->upsertByType(
            'web_footer',
            'Web Footer',
            120,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->footerOptionsForLocale('ar'),
                'en' => $this->footerOptionsForLocale('en'),
            ]),
            $now
        );
    }

    /**
     * Homepage chain: hero → prayer times (heading + description in block) → supplications (title + description + 3 cards)
     * → section divider (title + description for maps) → maps showcase (3 cards).
     * Clears prior rows of these types for the storefront theme code so re-seeding matches the template.
     */
    protected function seedStorefrontHomeSections(string $themeCode, string $defaultLocale, Carbon $now): void
    {
        $homeTypes = [
            ThemeCustomization::IMMERSIVE_HERO,
            ThemeCustomization::SECTION_DIVIDER,
            ThemeCustomization::PRAYER_TIMES,
            ThemeCustomization::SUPPLICATIONS_CONTENT,
            ThemeCustomization::MAPS_SHOWCASE,
        ];

        DB::table('shop_theme_customizations')
            ->where('theme_code', $themeCode)
            ->whereIn('type', $homeTypes)
            ->delete();

        $this->insertHomeThemeRow(
            ThemeCustomization::IMMERSIVE_HERO,
            'Home hero',
            10,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->immersiveHeroOptionsForLocale('ar'),
                'en' => $this->immersiveHeroOptionsForLocale('en'),
            ]),
            $now
        );

        $this->insertHomeThemeRow(
            ThemeCustomization::PRAYER_TIMES,
            'Prayer times',
            20,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->prayerTimesOptionsForLocale('ar'),
                'en' => $this->prayerTimesOptionsForLocale('en'),
            ]),
            $now
        );

        $this->insertHomeThemeRow(
            ThemeCustomization::SUPPLICATIONS_CONTENT,
            'Home supplications',
            30,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->supplicationsHomeOptionsForLocale('ar'),
                'en' => $this->supplicationsHomeOptionsForLocale('en'),
            ]),
            $now
        );

        $this->insertHomeThemeRow(
            ThemeCustomization::SECTION_DIVIDER,
            'Maps section intro',
            35,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->sectionDividerFullBleedOptionsForLocale('ar', 'maps'),
                'en' => $this->sectionDividerFullBleedOptionsForLocale('en', 'maps'),
            ]),
            $now
        );

        $this->insertHomeThemeRow(
            ThemeCustomization::MAPS_SHOWCASE,
            'Maps showcase',
            40,
            $themeCode,
            $this->buildLocalizableOptions($defaultLocale, [
                'ar' => $this->mapsShowcaseHomeOptionsForLocale('ar'),
                'en' => $this->mapsShowcaseHomeOptionsForLocale('en'),
            ]),
            $now
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function insertHomeThemeRow(
        string $type,
        string $name,
        int $sortOrder,
        string $themeCode,
        array $options,
        Carbon $now
    ): void {
        DB::table('shop_theme_customizations')->insert([
            'type'         => $type,
            'name'         => $name,
            'sort_order'   => $sortOrder,
            'status'       => true,
            'theme_code'   => $themeCode,
            'options'      => json_encode($options, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    /**
     * Full-bleed gradient band (same palette as inner hero) with title + description only.
     *
     * @return array<string, mixed>
     */
    protected function sectionDividerFullBleedOptionsForLocale(string $locale, string $segment): array
    {
        $p = 'seeders.web-theme.section-divider.'.$segment;

        return [
            'variant'         => 'full_bleed',
            'visible'         => true,
            'gradient_from'   => strtoupper(trim((string) config('web.section_divider.background.gradient_from', '#0D2A1A'))),
            'gradient_mid'    => strtoupper(trim((string) config('web.section_divider.background.gradient_mid', '#1A3A2A'))),
            'gradient_to'     => strtoupper(trim((string) config('web.section_divider.background.gradient_to', '#0D2A1A'))),
            'gold'            => strtoupper(trim((string) config('web.section_divider.background.gold', '#D4AF37'))),
            'wave_fill'       => strtoupper(trim((string) config('web.section_divider.background.wave_fill', '#FEFAF5'))),
            'badge_show'      => false,
            'badge_icon'      => '',
            'badge_text'      => '',
            'title'           => $this->t($p.'.title', $locale, ''),
            'description'     => $this->t($p.'.description', $locale, ''),
            'primary_show'    => false,
            'primary_label'   => '',
            'primary_url'     => '',
            'primary_icon'    => '',
            'secondary_show'  => false,
            'secondary_label' => '',
            'secondary_url'   => '',
            'secondary_icon'  => '',
            'parchment_start' => '',
            'parchment_mid'   => '',
            'parchment_end'   => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function prayerTimesOptionsForLocale(string $locale): array
    {
        return [
            'api_url'         => '',
            'heading'         => $this->t('seeders.web-theme.prayer-times.heading', $locale, ''),
            'description'     => $this->t('seeders.web-theme.prayer-times.description', $locale, ''),
            'location_label'  => $this->t('seeders.web-theme.prayer-times.location_label', $locale, 'Makkah'),
            'city'            => 'Makkah',
            'country'         => 'Saudi Arabia',
            'method'          => 2,
            'autoplay_ms'     => 4000,
            'hour12'          => true,
        ];
    }

    /**
     * Title and description from installer lang; cards use the same content as the adhkar page.
     *
     * @return array{heading: string, subheading: string, limit: int, show_more: bool, more_url: string}
     */
    protected function supplicationsHomeOptionsForLocale(string $locale): array
    {
        return [
            'heading'    => $this->t('seeders.web-theme.supplications.heading', $locale, ''),
            'subheading' => $this->t('seeders.web-theme.supplications.subheading', $locale, ''),
            'limit'      => 3,
            'show_more'  => true,
            'more_url'   => '',
        ];
    }

    /**
     * @return array{heading: string, subheading: string, limit: int, link_show: bool, link_label: string}
     */
    protected function mapsShowcaseHomeOptionsForLocale(string $locale): array
    {
        return [
            'heading'    => '',
            'subheading' => '',
            'limit'      => 3,
            'link_show'  => true,
            'link_label' => $this->t('seeders.web-theme.maps-showcase.link_all', $locale, 'View all maps'),
        ];
    }

    protected function seedCoreWebColors(Carbon $now): void
    {
        if (! Schema::hasTable('core_config')) {
            return;
        }

        $map = [
            'general.store.web.primary_color'                    => self::SHOP_PRIMARY,
            'general.store.web.accent_color'                     => self::SHOP_ACCENT,
            'general.store.web.icon_color'                       => self::SHOP_ICON,
            'general.store.web.badge_color'                      => self::GOLD,
            'general.store.web.inner_page_hero_gradient_from'   => '#0D2A1A',
            'general.store.web.inner_page_hero_gradient_mid'    => '#1A3A2A',
            'general.store.web.inner_page_hero_gradient_to'     => '#0D2A1A',
            'general.store.web.section_divider_gradient_from'   => '#0D2A1A',
            'general.store.web.section_divider_gradient_mid'    => '#1A3A2A',
            'general.store.web.section_divider_gradient_to'     => '#0D2A1A',
        ];

        foreach ($map as $code => $value) {
            $existing = DB::table('core_config')->where('code', $code)->first();
            if ($existing) {
                continue;
            }

            DB::table('core_config')->insert([
                'code'       => $code,
                'value'      => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Admin layout / login footer (general.settings.footer.label). Skips if already set.
     */
    protected function seedAdminPanelFooterLabel(Carbon $now, string $installerLocale): void
    {
        if (! Schema::hasTable('core_config')) {
            return;
        }

        $code = 'general.settings.footer.label';
        if (DB::table('core_config')->where('code', $code)->exists()) {
            return;
        }

        $value = '&copy; :year <strong>Manasik Al-Hajj</strong>. '
            .$this->t('seeders.web-theme.admin-footer-rights', $installerLocale, 'All rights reserved.');

        DB::table('core_config')->insert([
            'code'       => $code,
            'value'      => $value,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * @param  array<string, array<string, mixed>>  $translations
     * @return array<string, mixed>
     */
    protected function buildLocalizableOptions(string $defaultLocale, array $translations): array
    {
        $defaultLocale = strtolower($defaultLocale);
        if (! isset($translations[$defaultLocale])) {
            $defaultLocale = 'en';
        }

        return [
            'default_locale' => $defaultLocale,
            'translations'   => $translations,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function immersiveHeroOptionsForLocale(string $locale): array
    {
        $p = 'seeders.web-theme.immersive.slide1';

        return [
            'slides' => [
                [
                    'badge_icon'  => '',
                    'badge'       => $this->t($p.'.badge', $locale, ''),
                    'title'       => $this->t($p.'.title', $locale, ''),
                    'description' => $this->t($p.'.description', $locale, ''),
                    'image'       => WebThemeInstallAssets::MAKA_PUBLIC_RELATIVE_PATH,
                    'primary'     => [
                        'label' => $this->t($p.'.primary_label', $locale, ''),
                        'icon'  => '',
                        'url'   => '/maps',
                    ],
                    'secondary'   => [
                        'label' => $this->t($p.'.secondary_label', $locale, ''),
                        'icon'  => '',
                        'url'   => '/adhkar',
                    ],
                    'stats'       => [
                        ['number' => $this->t($p.'.stat1_number', $locale, ''), 'label' => $this->t($p.'.stat1_label', $locale, '')],
                        ['number' => $this->t($p.'.stat2_number', $locale, ''), 'label' => $this->t($p.'.stat2_label', $locale, '')],
                        ['number' => $this->t($p.'.stat3_number', $locale, ''), 'label' => $this->t($p.'.stat3_label', $locale, '')],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function headerOptionsForLocale(string $locale): array
    {
        return [
            'colors' => [
                'primary'   => self::SHOP_PRIMARY,
                'secondary' => self::SHOP_ACCENT,
            ],
            'brand'   => [
                'icon'      => 'fas fa-kaaba',
                'title'     => $this->t('seeders.web-theme.header.brand.title', $locale, 'Hajj rites'),
                'subtitle'  => '',
                'logo_path' => '',
            ],
            'nav_primary' => [
                ['page_key' => 'home', 'label' => $this->t('seeders.web-theme.header.nav.home', $locale, 'Home')],
                ['page_key' => 'services', 'label' => $this->t('seeders.web-theme.header.nav.services', $locale, 'Services')],
                ['page_key' => 'maps', 'label' => $this->t('seeders.web-theme.header.nav.maps', $locale, 'Maps')],
                ['page_key' => 'adhkar', 'label' => $this->t('seeders.web-theme.header.nav.adhkar', $locale, 'Dhikr & duas')],
            ],
            'nav_secondary' => [],
            'nav'           => [
                ['label' => $this->t('seeders.web-theme.header.nav.home', $locale, 'Home'), 'url' => '/', 'icon' => ''],
                ['label' => $this->t('seeders.web-theme.header.nav.services', $locale, 'Services'), 'url' => '#services', 'icon' => ''],
                ['label' => $this->t('seeders.web-theme.header.nav.maps', $locale, 'Maps'), 'url' => '/maps', 'icon' => ''],
                ['label' => $this->t('seeders.web-theme.header.nav.adhkar', $locale, 'Dhikr & duas'), 'url' => '/adhkar', 'icon' => ''],
            ],
            'lang'  => [
                'show_switcher' => true,
                'button_label'  => $this->t('seeders.web-theme.header.lang-button', $locale, 'Language'),
            ],
            'login' => [
                'show'  => true,
                'label' => $this->t('seeders.web-theme.header.login-label', $locale, 'Login'),
                'url'   => '/hajj/login',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function footerOptionsForLocale(string $locale): array
    {
        return [
            'enabled' => true,
            'visibility' => [
                'brand' => true,
                'social' => true,
                'explore' => true,
                'support' => true,
                'contact' => true,
                'subscribe' => false,
                'bottom' => true,
                'bottom_mini' => true,
            ],
            'effects' => [
                'back_to_top' => true,
            ],
            'colors' => [
                'primary'   => self::GOLD,
                'secondary' => self::DEEP_FOREST,
            ],
            'brand'   => [
                'icon'        => 'fas fa-kaaba',
                'title'       => $this->t('seeders.web-theme.footer.brand.title', $locale, ''),
                'description' => $this->t('seeders.web-theme.footer.brand.description', $locale, ''),
                'trust'       => $this->t('seeders.web-theme.footer.brand.trust', $locale, ''),
            ],
            'social'      => [
                ['icon' => 'fab fa-facebook-f', 'url' => '#', 'aria_label' => 'Facebook'],
                ['icon' => 'fab fa-instagram', 'url' => '#', 'aria_label' => 'Instagram'],
            ],
            'col_explore' => [
                'title'        => $this->t('seeders.web-theme.footer.col-explore.title', $locale, 'Explore'),
                'show_chevron' => true,
                'links'        => [
                    ['label' => $this->t('seeders.web-theme.header.nav.home', $locale, 'Home'), 'url' => '/'],
                    ['label' => $this->t('seeders.web-theme.header.nav.services', $locale, 'Services'), 'url' => '#'],
                    ['label' => $this->t('seeders.web-theme.header.nav.maps', $locale, 'Maps'), 'url' => '/maps'],
                    ['label' => $this->t('seeders.web-theme.header.nav.adhkar', $locale, 'Dhikr & duas'), 'url' => '/adhkar'],
                ],
            ],
            'col_support' => [
                'title'        => $this->t('seeders.web-theme.footer.col-support.title', $locale, 'Support'),
                'show_chevron' => true,
                'links'        => [
                    ['label' => $this->t('seeders.web-theme.footer.col-support.links.faq', $locale, 'FAQ'), 'url' => '#'],
                    ['label' => $this->t('seeders.web-theme.footer.col-support.links.contact', $locale, 'Contact'), 'url' => '#'],
                    ['label' => $this->t('seeders.web-theme.footer.col-support.links.privacy', $locale, 'Privacy'), 'url' => '#'],
                ],
            ],
            'contact' => [
                'title' => $this->t('seeders.web-theme.footer.contact.title', $locale, 'Contact'),
                'items' => [
                    ['icon' => 'fas fa-phone-alt', 'text' => $this->t('seeders.web-theme.footer.contact.phone', $locale, '')],
                    ['icon' => 'fas fa-envelope', 'text' => $this->t('seeders.web-theme.footer.contact.email', $locale, '')],
                    ['icon' => 'fas fa-map-marker-alt', 'text' => $this->t('seeders.web-theme.footer.contact.address', $locale, '')],
                ],
            ],
            'subscribe' => [
                'title'       => $this->t('seeders.web-theme.footer.subscribe.title', $locale, ''),
                'placeholder' => $this->t('seeders.web-theme.footer.subscribe.placeholder', $locale, ''),
                'privacy'     => $this->t('seeders.web-theme.footer.subscribe.privacy', $locale, ''),
                'success_msg' => $this->t('seeders.web-theme.footer.subscribe.success', $locale, ''),
                'invalid_msg' => $this->t('seeders.web-theme.footer.subscribe.invalid', $locale, ''),
            ],
            'bottom' => [
                'copyright'      => $this->t('seeders.web-theme.footer.bottom.copyright', $locale, '')
                    .' © '.date('Y').' '.$this->t('seeders.web-theme.footer.brand.title', $locale, ''),
                'mini_nav_label' => $this->t('seeders.web-theme.footer.bottom.mini-nav-label', $locale, ''),
                'links'          => [
                    ['label' => $this->t('seeders.web-theme.footer.bottom.links.terms', $locale, ''), 'url' => '#'],
                    ['label' => $this->t('seeders.web-theme.footer.bottom.links.privacy', $locale, ''), 'url' => '#'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function innerPageHeroOptionsForLocale(string $locale): array
    {
        return [
            'visible'       => true,
            'gradient_from' => strtoupper(trim((string) config('web.inner_page_hero.background.gradient_from', '#0D2A1A'))),
            'gradient_mid'  => strtoupper(trim((string) config('web.inner_page_hero.background.gradient_mid', '#1A3A2A'))),
            'gradient_to'   => strtoupper(trim((string) config('web.inner_page_hero.background.gradient_to', '#0D2A1A'))),
            'gold'          => strtoupper(trim((string) config('web.inner_page_hero.background.gold', '#D4AF37'))),
            'wave_fill'     => strtoupper(trim((string) config('web.inner_page_hero.background.wave_fill', '#FEFAF5'))),
            'pages'         => [
                'services' => $this->innerHeroPageSeed('services', $locale),
                'maps'     => $this->innerHeroPageSeed('maps', $locale),
                'adhkar'   => $this->innerHeroPageSeed('adhkar', $locale),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function innerHeroPageSeed(string $page, string $locale): array
    {
        $p = 'seeders.web-theme.inner-page-hero.'.$page;

        return [
            'badge_show'      => true,
            'badge_icon'      => '',
            'badge_text'      => $this->t($p.'.badge', $locale, ''),
            'title'           => $this->t($p.'.title', $locale, ''),
            'description'     => $this->t($p.'.description', $locale, ''),
            'primary_show'    => true,
            'primary_label'   => $this->t($p.'.primary_label', $locale, ''),
            'primary_url'     => '',
            'primary_icon'    => '',
            'secondary_show'  => true,
            'secondary_label' => $this->t($p.'.secondary_label', $locale, ''),
            'secondary_url'   => '',
            'secondary_icon'  => '',
        ];
    }

    protected function t(string $key, string $locale, string $fallback): string
    {
        $fullKey = 'installer::app.'.$key;

        $translated = (string) Lang::get($fullKey, [], $locale);
        if ($translated !== '' && $translated !== $fullKey) {
            return $translated;
        }

        $en = (string) Lang::get($fullKey, [], 'en');
        if ($en !== '' && $en !== $fullKey) {
            return $en;
        }

        return $fallback;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function upsertByType(
        string $type,
        string $name,
        int $sortOrder,
        string $themeCode,
        array $options,
        Carbon $now
    ): void {
        $existing = DB::table('shop_theme_customizations')
            ->where('theme_code', $themeCode)
            ->where('type', $type)
            ->orderBy('id')
            ->first();

        if ($existing) {
            DB::table('shop_theme_customizations')
                ->where('id', $existing->id)
                ->update([
                    'status'     => true,
                    'updated_at' => $now,
                    'options'    => ($existing->options !== null && $existing->options !== '')
                        ? $existing->options
                        : json_encode($options),
                ]);

            return;
        }

        DB::table('shop_theme_customizations')->insert([
            'type'       => $type,
            'name'       => $name,
            'sort_order' => $sortOrder,
            'status'     => true,
            'theme_code' => $themeCode,
            'options'    => json_encode($options),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
