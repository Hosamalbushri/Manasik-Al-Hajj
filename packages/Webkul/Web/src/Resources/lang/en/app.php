<?php

return [
    'meta' => [
        'title' => 'Student portal — Events',
    ],

    'layout' => [
        'brand' => 'Student portal',
        'nav' => [
            'home'           => 'Home',
            'events'         => 'Events',
            'student-login'  => 'Student sign-in',
            'student-logout' => 'Sign out',
            'student-menu'   => 'Student account menu',
            'student-registration'   => 'Registration no.',
            'student-account-edit' => 'Edit account',
            'student-events'       => 'My events',
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
                        'search'     => 'Search events',
                        'search-text'=> 'Search events…',
                    ],
                ],
                'mobile' => [
                    'logo-alt'   => 'Home',
                    'menu'       => 'Open menu',
                    'search'     => 'Search events',
                    'search-text'=> 'Search events…',
                ],
            ],
            'footer' => [
                'link-student-login' => 'Student sign-in',
            ],
            'flash-group' => [
                'close' => 'Dismiss notification',
            ],
            'services' => [
                'calendar-title' => 'Event calendar',
                'calendar-desc'  => 'See what is on and plan ahead.',
                'updates-title'  => 'Stay informed',
                'updates-desc'   => 'Descriptions and details in one place.',
                'campus-title'   => 'Campus & venues',
                'campus-desc'    => 'Know where activities take place.',
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
            'schedule' => 'Schedule',
            'maps'     => 'Maps',
            'adhkar'   => 'Dhikr & duas',
        ],
    ],

    'adhkar' => [
        'meta_title'       => 'Dhikr & duas',
        'meta_description' => 'Duas and dhikr for pilgrims.',
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
        'meta_title'       => 'Holy rites sites — maps & cards',
        'meta_description' => 'Maps for Hajj and Umrah sites.',
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
        'title' => 'Web Home',
        'seo' => [
            'meta-title'       => 'Student portal',
            'meta-description' => 'Browse events and updates for students.',
            'meta-keywords'    => 'students, events, portal',
        ],
        'index' => [
            'image-carousel'      => 'Featured images',
            'events-carousel'     => 'Featured events',
            'categories-carousel' => 'Event categories',
            'footer-links'        => 'Homepage link columns',
            'services-strip'      => 'Services and features',
            'immersive-hero'      => 'Immersive hero showcase',
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
            'Students',
        ],
        'mobile_menu_title' => 'Site links',
        'copyright' => '© :year — Web portal. All rights reserved.',
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
