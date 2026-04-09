<?php

return [
    'meta' => [
        'title' => 'بوابة الطلاب — الفعاليات',
    ],

    'layout' => [
        'brand' => 'بوابة الطلاب',
        'nav' => [
            'home'           => 'الرئيسية',
            'events'         => 'الفعاليات',
            'student-login'  => 'تسجيل الدخول',
            'student-logout' => 'تسجيل الخروج',
            'student-menu'   => 'قائمة حساب الطالب',
            'student-registration'   => 'رقم القيد',
            'student-account-edit' => 'تعديل الحساب',
            'student-events'       => 'استعراض الفعاليات',
        ],
    ],

    'components' => [
        'layouts' => [
            'skip-to-content' => 'تخطي إلى المحتوى الرئيسي',
            'header' => [
                'desktop' => [
                    'bottom' => [
                        'logo-alt'   => 'الرئيسية',
                        'nav-label'  => 'التنقل الرئيسي',
                        'search'     => 'البحث في الفعاليات',
                        'search-text'=> 'ابحث في الفعاليات…',
                    ],
                ],
                'mobile' => [
                    'logo-alt'   => 'الرئيسية',
                    'menu'       => 'فتح القائمة',
                    'search'     => 'البحث في الفعاليات',
                    'search-text'=> 'ابحث في الفعاليات…',
                ],
            ],
            'footer' => [
                'link-student-login' => 'تسجيل دخول الطلاب',
            ],
            'flash-group' => [
                'close' => 'إغلاق الإشعار',
            ],
            'services' => [
                'calendar-title' => 'تقويم الفعاليات',
                'calendar-desc'  => 'اطلع على المواعيد وخطط مسبقاً.',
                'updates-title'  => 'ابقَ على اطلاع',
                'updates-desc'   => 'التفاصيل في مكان واحد.',
                'campus-title'   => 'الحرم والمواقع',
                'campus-desc'    => 'اعرف أين تقام الأنشطة.',
            ],
        ],
    ],

    'student' => [
        'account' => [
            'fields' => [
                'major' => 'التخصص',
            ],
        ],
    ],

    'header' => [
        'lang_button' => 'اللغة',
        'login' => 'تسجيل الدخول',
        'close_menu' => 'إغلاق القائمة',
    ],

    'home' => [
        'title' => 'واجهة الويب',
        'seo' => [
            'meta-title'       => 'بوابة الطلاب',
            'meta-description' => 'استعرض الفعاليات والتحديثات للطلاب.',
            'meta-keywords'    => 'طلاب، فعاليات، بوابة',
        ],
        'index' => [
            'image-carousel'      => 'صور مميزة',
            'events-carousel'     => 'فعاليات مميزة',
            'categories-carousel' => 'تصنيفات الفعاليات',
            'footer-links'        => 'أعمدة روابط في الصفحة الرئيسية',
            'services-strip'      => 'الخدمات والمميزات',
            'immersive-hero'      => 'عرض البطل التفاعلي',
        ],
        'carousel' => [
            'slide-alt'   => 'صورة العرض :n',
            'slide-label' => 'شريحة :current من :total',
            'prev'        => 'الشريحة السابقة',
            'next'        => 'الشريحة التالية',
            'dots-label'  => 'مؤشرات الشرائح',
            'dot-go-to'   => 'الانتقال إلى الشريحة :n',
        ],
        'immersive-hero' => [
            'typing-fallback' => 'فعاليات',
        ],
        'aria' => [
            'hero' => 'واجهة ويب رئيسية',
            'links' => 'روابط واجهة الويب',
        ],
        'hero' => [
            'badge' => 'منصة الويب',
            'line1' => 'حلول رقمية عصرية',
            'highlight' => 'بنفس تصميم المتجر',
            'typing-prefix' => 'نقدم لك:',
            'typing-words' => [
                'تجربة سريعة',
                'واجهة واضحة',
                'تصميم احترافي',
            ],
            'description' => 'هذه الصفحة تستخدم مكونات الواجهات المنسوخة من المتجر داخل حزمة Web.',
            'primary-cta' => 'ابدأ الآن',
            'secondary-cta' => 'تعرف أكثر',
            'scroll-hint' => 'اسحب للأسفل',
            'cards' => [
                [
                    'date' => '2026',
                    'title' => 'واجهة جاهزة للإطلاق',
                    'attendees' => 'واجهة جاهزة',
                ],
                [
                    'date' => 'Reusable',
                    'title' => 'مبنية على مكونات',
                    'attendees' => 'مكونات قابلة لإعادة الاستخدام',
                ],
                [
                    'date' => 'Modern',
                    'title' => 'تصميم متناسق',
                    'attendees' => 'هوية بصرية متناسقة',
                ],
            ],
        ],
        'links' => [
            'home' => 'الرئيسية',
            'services' => 'الخدمات',
            'about' => 'من نحن',
            'contact' => 'تواصل معنا',
        ],
    ],

    'footer' => [
        'brand_line' => 'نرافقك في رحلتك الرقمية بخدمات واضحة وموثوقة.',
        'column_titles' => [
            'تصفح',
            'عن المنصة',
            'الطلاب',
        ],
        'mobile_menu_title' => 'روابط الموقع',
        'copyright' => '© :year — منصة الويب. جميع الحقوق محفوظة.',
    ],

    'manasik_footer' => [
        'brand' => 'مناسك الحج',
        'description' => 'دليلك الموثوق لأداء مناسك الحج والعمرة بكل يسر وطمأنينة.',
        'trust' => 'معتمد من الهيئة السعودية للسياحة',
        'titles' => [
            'explore' => 'استكشف',
            'support' => 'الدعم',
            'contact' => 'تواصل معنا',
            'subscribe' => 'اشترك',
        ],
        'links' => [
            'home' => 'الرئيسية',
            'hajj' => 'مناسك الحج',
            'umrah' => 'مناسك العمرة',
            'calendar' => 'المواعيد والتقويم',
            'maps' => 'الخرائط التفاعلية',
            'help' => 'مركز المساعدة',
            'faq' => 'الأسئلة الشائعة',
            'terms' => 'الشروط والأحكام',
            'privacy' => 'سياسة الخصوصية',
            'contact' => 'اتصل بنا',
        ],
        'contact' => [
            'address' => 'مكة المكرمة، السعودية',
            'phone' => '+966 12 345 6789',
            'email' => 'info@manasik.com',
        ],
        'nl_placeholder' => 'بريدك الإلكتروني',
        'nl_privacy' => 'لن نرسل بريداً مزعجاً أبداً',
        'nl_success' => 'تم الاشتراك بنجاح.',
        'nl_invalid' => 'الرجاء إدخال بريد إلكتروني صحيح.',
        'copyright' => '١٤٤٦هـ — :year مناسك الحج',
        'mini' => [
            'sitemap' => 'خريطة الموقع',
            'accessibility' => 'إمكانية الوصول',
            'developers' => 'المطورون',
        ],
        'mini_nav' => 'روابط أسفل الصفحة',
        'back_top' => 'العودة للأعلى',
    ],
];
