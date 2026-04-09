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
