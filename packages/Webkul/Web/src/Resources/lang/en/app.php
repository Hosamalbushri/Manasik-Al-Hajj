<?php

return [
    'meta' => [
        'title' => 'Manasik Al-Hajj — Digital portal for Hajj and Umrah',
    ],

    'layout' => [
        'brand' => 'Manasik Al-Hajj',
        'nav' => [
            'home'           => 'Home',
            'events'         => 'Announcements',
            'student-login'  => 'Pilgrim sign-in',
            'student-logout' => 'Sign out',
            'student-menu'   => 'Account menu',
            'student-registration'   => 'Registration number',
            'student-account-edit' => 'Edit account details',
            'student-events'       => 'View announcements',
        ],
    ],

    'components' => [
        'layouts' => [
            'skip-to-content' => 'Skip to main content',
            'header' => [
                'desktop' => [
                    'bottom' => [
                        'logo-alt'   => 'Home',
                        'nav-label'  => 'Main navigation',
                        'search'     => 'Search the portal',
                        'search-text'=> 'Search content…',
                    ],
                ],
                'mobile' => [
                    'logo-alt'   => 'Home',
                    'menu'       => 'Open menu',
                    'search'     => 'Search the portal',
                    'search-text'=> 'Search content…',
                ],
            ],
            'footer' => [
                'link-student-login' => 'Sign in',
            ],
            'flash-group' => [
                'close' => 'Dismiss notification',
            ],
            'services' => [
                'calendar-title' => 'Dates & calendar',
                'calendar-desc'  => 'View important dates and plan your journey.',
                'updates-title'  => 'News & updates',
                'updates-desc'   => 'Trusted information in one place.',
                'campus-title'   => 'Holy sites & locations',
                'campus-desc'    => 'Learn about the principal sacred places.',
            ],
        ],
    ],

    'student' => [
        'account' => [
            'fields' => [
                'major' => 'Major',
            ],
        ],
    ],

    'header' => [
        'lang_button' => 'Language',
        'login' => 'Sign in',
        'close_menu' => 'Close menu',
    ],

    'inner_hero' => [
        'breadcrumb_aria' => 'Breadcrumb',
        'nav_fallback_labels' => [
            'home'     => 'Home',
            'services' => 'Services',
            'maps'     => 'Maps',
            'adhkar'   => 'Supplications & adhkar',
        ],
    ],

    'adhkar' => [
        'meta_title'       => 'Supplications and adhkar — Manasik Al-Hajj',
        'meta_description' => 'Authoritative supplications and remembrances for Hajj and Umrah pilgrims, arranged by category for easy reference.',
        'tabs_aria'        => 'Dhikr and dua categories',
        'copy'             => 'Copy',
        'favorite_btn'     => 'Save',
        'favorite_aria'    => 'Save',
        'toast_copied'     => 'Copied',
        'toast_copy_failed'=> 'Copy failed',
        'toast_fav_added'  => 'Saved',
        'toast_fav_removed'=> 'Removed',
        'empty'            => 'No content yet.',
        'empty_title'      => 'No content',
        'empty_hint'       => 'No duas yet.',
        'empty_btn_home'   => 'Home',
    ],

    'maps' => [
        'meta_title'       => 'Holy sites — maps and directory',
        'meta_description' => 'Official guide to the principal locations for Hajj and Umrah: Makkah al-Mukarramah, al-Madinah al-Munawwarah, and the sacred sites of the rites.',
        'section_title'    => 'Holy rites sites',
        'section_subtitle' => 'Learn about the key places you will visit on Hajj and Umrah.',
        'features_title'   => 'Places',
        'btn_details'      => 'More details',
        'btn_show_map'     => 'Show map',
        'btn_hide_map'     => 'Hide map',
        'empty'            => 'No map locations to display yet.',
        'empty_title'      => 'No maps',
        'empty_hint'       => 'No content yet.',
        'empty_btn_home'   => 'Home',
        'cards'            => [
            'makkah' => [
                'title'         => 'Makkah al-Mukarramah',
                'badge'         => 'Holiest place on earth',
                'description'   => 'The holiest city in Islam, home to al-Masjid al-Haram and the Kaaba. The qiblah for prayer and destination of pilgrims.',
                'features'      => ['Al-Masjid al-Haram', 'The Kaaba', 'Zamzam', 'Islamic libraries', 'Guidance centres', 'Nearby hotels'],
                'detail_alert'  => 'Makkah is the holiest city in Islam. It hosts al-Masjid al-Haram and the Kaaba. Population: about 2 million. Elevation: about 277 m above sea level.',
                'image_alt'     => 'Makkah al-Mukarramah',
            ],
            'mina' => [
                'title'         => 'Mina',
                'badge'         => 'City of tents',
                'description'   => 'The site of Mina, where pilgrims spend the days of tashriq and perform the jamarat. Capacity for over 2.6 million pilgrims.',
                'features'      => ['Tent city', 'Jamarat bridge', 'Many mosques', 'Medical centres', 'Air-conditioned camps', 'Restrooms'],
                'detail_alert'  => 'Mina lies between Makkah and Muzdalifah. About 7 km from Makkah. Hosts over 2.6 million pilgrims. Area about 8 km².',
                'image_alt'     => 'Mina',
            ],
            'arafat' => [
                'title'         => 'Arafat',
                'badge'         => 'The greatest pillar of Hajj',
                'description'   => 'The most important site of Hajj, where pilgrims stand on 9 Dhu al-Hijjah. The Prophet ﷺ said: “Hajj is Arafat.”',
                'features'      => ['Jabal al-Rahmah', 'Masjid Nimrah', 'Camps', 'First aid', 'Restrooms', 'Bus parking'],
                'detail_alert'  => 'Arafat is about 20 km east of Makkah. Area about 10.4 km². Pilgrims stand from noon until sunset on the day of Arafah.',
                'image_alt'     => 'Arafat',
            ],
            'muzdalifah' => [
                'title'         => 'Muzdalifah',
                'badge'         => 'The sacred mash’ar',
                'description'   => 'The sacred mash’ar where pilgrims spend the night of 10 Dhu al-Hijjah and collect pebbles for the jamarat.',
                'features'      => ['Sacred mash’ar', 'Muzdalifah mosque', 'Camps', 'Service centres', 'Restrooms', 'Full lighting'],
                'detail_alert'  => 'Muzdalifah lies between Arafat and Mina. Pilgrims stay overnight on 10 Dhu al-Hijjah and collect seven pebbles for stoning.',
                'image_alt'     => 'Muzdalifah',
            ],
            'jamarat' => [
                'title'         => 'Jamarat al-‘Aqaba',
                'badge'         => 'Stoning the devil',
                'description'   => 'The largest of the three jamarat, stoned with seven pebbles on the Day of Sacrifice and the days of tashriq, in remembrance of Ibrahim’s trial.',
                'features'      => ['Jamarat bridge', 'Large jamrah', 'Air-conditioned passages', 'CCTV', 'First aid', 'Escalators'],
                'detail_alert'  => 'Jamarat al-‘Aqaba is the jamrah closest to Makkah. The jamarat bridge was developed to handle up to 300,000 pilgrims per hour.',
                'image_alt'     => 'Jamarat',
            ],
            'madinah' => [
                'title'         => 'Madinah al-Munawwarah',
                'badge'         => 'Taybah the pure',
                'description'   => 'The city of the Prophet ﷺ, with al-Masjid an-Nabawi, the Green Dome, and a garden from the gardens of Paradise.',
                'features'      => ['Al-Masjid an-Nabawi', 'Green Dome', 'Rawdah', 'Islamic libraries', 'Nearby hotels', 'Guidance centres'],
                'detail_alert'  => 'Madinah is the city of the Prophet ﷺ. Population about 1.2 million. Known as Taybah the pure.',
                'image_alt'     => 'Madinah al-Munawwarah',
            ],
        ],
    ],

    'home' => [
        'title' => 'Home — Manasik Al-Hajj',
        'seo' => [
            'meta-title'       => 'Manasik Al-Hajj — Home',
            'meta-description' => 'Official Manasik Al-Hajj portal: daily prayer times, authenticated supplications and adhkar, and an illustrated directory of the holy sites for Hajj and Umrah.',
            'meta-keywords'    => 'Hajj, Umrah, Makkah, Madinah, prayer times, supplications, holy sites, pilgrimage, Manasik Al-Hajj',
        ],
        'index' => [
            'image-carousel'      => 'Featured image carousel',
            'events-carousel'     => 'Featured announcements',
            'categories-carousel' => 'Content categories',
            'footer-links'        => 'Footer navigation',
            'services-strip'      => 'Services overview',
            'immersive-hero'      => 'Homepage main banner',
            'supplications'       => 'Supplications and adhkar',
            'section-divider'     => 'Section heading band',
            'maps-showcase'       => 'Holy sites — homepage preview',
            'prayer-times'        => 'Daily prayer times',
        ],
        'maps-showcase' => [
            'link-default' => 'View all maps',
        ],
        'prayer-times' => [
            'default_heading' => 'Prayer times',
            'default_location' => 'Makkah',
            'fajr' => 'Fajr',
            'sunrise' => 'Sunrise',
            'dhuhr' => 'Dhuhr',
            'asr' => 'Asr',
            'maghrib' => 'Maghrib',
            'isha' => 'Isha',
            'next_label' => 'Next prayer',
            'updated_note' => 'Times refresh automatically',
            'am' => 'AM',
            'pm' => 'PM',
            'pagination_label' => 'Prayer time slides',
        ],
        'supplications' => [
            'source_label' => 'Source',
            'view_more' => 'View more',
        ],
        'carousel' => [
            'slide-alt'   => 'Carousel image :n',
            'slide-label' => 'Slide :current of :total',
            'prev'        => 'Previous slide',
            'next'        => 'Next slide',
            'dots-label'  => 'Slide markers',
            'dot-go-to'   => 'Go to slide :n',
        ],
        'immersive-hero' => [
            'typing-fallback' => 'events',
        ],
        'aria' => [
            'hero' => 'Web hero section',
            'links' => 'Web links section',
        ],
        'hero' => [
            'badge' => 'Web Platform',
            'line1' => 'Modern digital solutions',
            'highlight' => 'With the same shop design',
            'typing-prefix' => 'We offer you:',
            'typing-words' => [
                'Fast experience',
                'Clear interface',
                'Professional design',
            ],
            'description' => 'This page uses UI components copied from the shop package inside the Web package.',
            'primary-cta' => 'Start Now',
            'secondary-cta' => 'Learn More',
            'scroll-hint' => 'Scroll down',
            'cards' => [
                [
                    'date' => '2026',
                    'title' => 'Launch Ready UI',
                    'attendees' => 'Ready interface',
                ],
                [
                    'date' => 'Reusable',
                    'title' => 'Component Based',
                    'attendees' => 'Reusable components',
                ],
                [
                    'date' => 'Modern',
                    'title' => 'Consistent Design',
                    'attendees' => 'Consistent visual identity',
                ],
            ],
        ],
        'links' => [
            'home' => 'Home',
            'services' => 'Services',
            'about' => 'About Us',
            'contact' => 'Contact Us',
        ],
    ],

    'footer' => [
        'brand_line' => 'A clear, reliable web experience for your journey.',
        'column_titles' => [
            'Browse',
            'About',
            'Pilgrims',
        ],
        'mobile_menu_title' => 'Site links',
        'copyright' => '© :year — Manasik Al-Hajj. All rights reserved.',
    ],

    'manasik_footer' => [
        'brand' => 'Manasik Al-Hajj',
        'description' => 'Your trusted guide to performing Hajj and Umrah rituals with ease and peace of mind.',
        'trust' => 'Approved by the Saudi Tourism Authority',
        'titles' => [
            'explore' => 'Explore',
            'support' => 'Support',
            'contact' => 'Contact us',
            'subscribe' => 'Subscribe',
        ],
        'links' => [
            'home' => 'Home',
            'hajj' => 'Hajj rituals',
            'umrah' => 'Umrah rituals',
            'calendar' => 'Dates & calendar',
            'maps' => 'Interactive maps',
            'help' => 'Help center',
            'faq' => 'FAQ',
            'terms' => 'Terms & conditions',
            'privacy' => 'Privacy policy',
            'contact' => 'Contact us',
        ],
        'contact' => [
            'address' => 'Makkah, Saudi Arabia',
            'phone' => '+966 12 345 6789',
            'email' => 'info@manasik.com',
        ],
        'nl_placeholder' => 'Your email',
        'nl_privacy' => 'We will never send spam.',
        'nl_success' => 'Subscribed successfully.',
        'nl_invalid' => 'Please enter a valid email address.',
        'copyright' => '1446 AH — :year Manasik Al-Hajj',
        'mini' => [
            'sitemap' => 'Site map',
            'accessibility' => 'Accessibility',
            'developers' => 'Developers',
        ],
        'mini_nav' => 'Footer links',
        'back_top' => 'Back to top',
    ],
];
