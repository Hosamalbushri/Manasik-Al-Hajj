<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;

class WebThemeDefaultsSeeder extends Seeder
{
    /**
     * Seed default web header/footer customizations.
     *
     * @param  array  $parameters
     */
    public function run(array $parameters = []): void
    {
        if (! Schema::hasTable('shop_theme_customizations')) {
            return;
        }

        $themeCode = (string) config('web.storefront_theme_code', 'web');
        $defaultLocale = strtolower((string) ($parameters['default_store_locale'] ?? $parameters['locale'] ?? config('app.locale', 'en')));
        $now = now();

        $headerOptions = [
            'default_locale' => $defaultLocale,
            'translations'   => [
                $defaultLocale => [
                    'colors' => [
                        'primary'   => '#1F6E2F',
                        'secondary' => '#2C8E3C',
                    ],
                    'brand'   => [
                        'icon'      => 'fas fa-kaaba',
                        'title'     => $this->t('seeders.web-theme.header.brand.title', $defaultLocale, 'Krayin'),
                        'subtitle'  => $this->t('seeders.web-theme.header.brand.subtitle', $defaultLocale, '| CRM'),
                        'logo_path' => '',
                    ],
                    'nav_primary' => [
                        ['page_key' => 'home', 'label' => $this->t('seeders.web-theme.header.nav.home', $defaultLocale, 'Home')],
                        ['page_key' => 'services', 'label' => $this->t('seeders.web-theme.header.nav.services', $defaultLocale, 'Services')],
                        ['page_key' => 'schedule', 'label' => $this->t('seeders.web-theme.header.nav.schedule', $defaultLocale, 'Schedule')],
                        ['page_key' => 'maps', 'label' => $this->t('seeders.web-theme.header.nav.maps', $defaultLocale, 'Maps')],
                    ],
                    'nav_secondary' => [],
                    'nav'           => [
                        ['label' => $this->t('seeders.web-theme.header.nav.home', $defaultLocale, 'Home'), 'url' => '/', 'icon' => ''],
                        ['label' => $this->t('seeders.web-theme.header.nav.services', $defaultLocale, 'Services'), 'url' => '#services', 'icon' => ''],
                        ['label' => $this->t('seeders.web-theme.header.nav.schedule', $defaultLocale, 'Schedule'), 'url' => '#schedule', 'icon' => ''],
                        ['label' => $this->t('seeders.web-theme.header.nav.maps', $defaultLocale, 'Maps'), 'url' => '#maps', 'icon' => ''],
                    ],
                    'lang'  => [
                        'show_switcher' => true,
                        'button_label'  => $this->t('seeders.web-theme.header.lang-button', $defaultLocale, 'Language'),
                    ],
                    'login' => [
                        'show'  => true,
                        'label' => $this->t('seeders.web-theme.header.login-label', $defaultLocale, 'Login'),
                        'url'   => '/login',
                    ],
                ],
            ],
        ];

        $footerOptions = [
            'default_locale' => $defaultLocale,
            'translations'   => [
                $defaultLocale => [
                    'enabled' => true,
                    'visibility' => [
                        'brand' => true,
                        'social' => true,
                        'explore' => true,
                        'support' => true,
                        'contact' => true,
                        'subscribe' => true,
                        'bottom' => true,
                        'bottom_mini' => true,
                    ],
                    'effects' => [
                        'back_to_top' => true,
                    ],
                    'colors' => [
                        'primary'   => '#D4AF37',
                        'secondary' => '#0D2A1A',
                    ],
                    'brand'   => [
                        'icon'        => 'fas fa-kaaba',
                        'title'       => $this->t('seeders.web-theme.footer.brand.title', $defaultLocale, 'Krayin'),
                        'description' => $this->t('seeders.web-theme.footer.brand.description', $defaultLocale, 'A digital platform to serve your customers better.'),
                        'trust'       => $this->t('seeders.web-theme.footer.brand.trust', $defaultLocale, 'Trusted service, 24/7 support'),
                    ],
                    'social'      => [
                        ['icon' => 'fab fa-facebook-f', 'url' => '#', 'aria_label' => 'Facebook'],
                        ['icon' => 'fab fa-x-twitter', 'url' => '#', 'aria_label' => 'X'],
                        ['icon' => 'fab fa-instagram', 'url' => '#', 'aria_label' => 'Instagram'],
                    ],
                    'col_explore' => [
                        'title'        => $this->t('seeders.web-theme.footer.col-explore.title', $defaultLocale, 'Explore'),
                        'show_chevron' => true,
                        'links'        => [
                            ['label' => $this->t('seeders.web-theme.header.nav.home', $defaultLocale, 'Home'), 'url' => '/'],
                            ['label' => $this->t('seeders.web-theme.header.nav.services', $defaultLocale, 'Services'), 'url' => '#'],
                            ['label' => $this->t('seeders.web-theme.header.nav.maps', $defaultLocale, 'Maps'), 'url' => '#'],
                        ],
                    ],
                    'col_support' => [
                        'title'        => $this->t('seeders.web-theme.footer.col-support.title', $defaultLocale, 'Support'),
                        'show_chevron' => true,
                        'links'        => [
                            ['label' => $this->t('seeders.web-theme.footer.col-support.links.faq', $defaultLocale, 'FAQ'), 'url' => '#'],
                            ['label' => $this->t('seeders.web-theme.footer.col-support.links.contact', $defaultLocale, 'Contact Us'), 'url' => '#'],
                            ['label' => $this->t('seeders.web-theme.footer.col-support.links.privacy', $defaultLocale, 'Privacy Policy'), 'url' => '#'],
                        ],
                    ],
                    'contact' => [
                        'title' => $this->t('seeders.web-theme.footer.contact.title', $defaultLocale, 'Contact'),
                        'items' => [
                            ['icon' => 'fas fa-phone-alt', 'text' => $this->t('seeders.web-theme.footer.contact.phone', $defaultLocale, '+966 50 000 0000')],
                            ['icon' => 'fas fa-envelope', 'text' => $this->t('seeders.web-theme.footer.contact.email', $defaultLocale, 'info@example.com')],
                            ['icon' => 'fas fa-map-marker-alt', 'text' => $this->t('seeders.web-theme.footer.contact.address', $defaultLocale, 'Makkah - Saudi Arabia')],
                        ],
                    ],
                    'subscribe' => [
                        'title'       => $this->t('seeders.web-theme.footer.subscribe.title', $defaultLocale, 'Subscribe to our newsletter'),
                        'placeholder' => $this->t('seeders.web-theme.footer.subscribe.placeholder', $defaultLocale, 'Enter your email'),
                        'privacy'     => $this->t('seeders.web-theme.footer.subscribe.privacy', $defaultLocale, 'By subscribing, you agree to the privacy policy.'),
                        'success_msg' => $this->t('seeders.web-theme.footer.subscribe.success', $defaultLocale, 'Subscribed successfully.'),
                        'invalid_msg' => $this->t('seeders.web-theme.footer.subscribe.invalid', $defaultLocale, 'Please enter a valid email address.'),
                    ],
                    'bottom' => [
                        'copyright'      => $this->t('seeders.web-theme.footer.bottom.copyright', $defaultLocale, 'All rights reserved')
                            .' © '.date('Y').' '.$this->t('seeders.web-theme.footer.brand.title', $defaultLocale, 'Krayin'),
                        'mini_nav_label' => $this->t('seeders.web-theme.footer.bottom.mini-nav-label', $defaultLocale, 'Quick Links'),
                        'links'          => [
                            ['label' => $this->t('seeders.web-theme.footer.bottom.links.terms', $defaultLocale, 'Terms & Conditions'), 'url' => '#'],
                            ['label' => $this->t('seeders.web-theme.footer.bottom.links.privacy', $defaultLocale, 'Privacy Policy'), 'url' => '#'],
                        ],
                    ],
                ],
            ],
        ];

        $this->upsertByType('web_header', 'Web Header', 10, $themeCode, $headerOptions, $now);
        $this->upsertByType('web_footer', 'Web Footer', 20, $themeCode, $footerOptions, $now);
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
                    // Keep existing user customizations intact if already present.
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

