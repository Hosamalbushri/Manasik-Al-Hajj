<?php

namespace Webkul\Manasik\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WebDuaDefaultsSeeder extends Seeder
{
    /**
     * Seed default dua sections and duas when tables are empty (first install / manual run).
     */
    public function run(): void
    {
        if (! Schema::hasTable('manasik_dua_sections') || ! Schema::hasTable('manasik_duas')) {
            return;
        }

        if (DB::table('manasik_dua_sections')->exists()) {
            return;
        }

        $defaultLocale = strtolower((string) config('app.locale', 'en'));
        $now = now();

        $sections = [
            [
                'slug' => 'morning-evening',
                'sort_order' => 0,
                'content' => [
                    'default_locale' => $defaultLocale,
                    'translations' => [
                        'ar' => ['title' => 'أذكار الصباح والمساء'],
                        'en' => ['title' => 'Morning & evening adhkar'],
                    ],
                ],
                'duas' => [
                    [
                        'sort_order' => 0,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'عند الاستيقاظ',
                                    'text' => 'الْحَمْدُ لِلَّهِ الَّذِي أَحْيَانَا بَعْدَ مَا أَمَاتَنَا وَإِلَيْهِ النُّشُورُ',
                                    'reference' => 'رواه البخاري ومسلم',
                                ],
                                'en' => [
                                    'title' => 'Upon waking',
                                    'text' => 'All praise is for Allah who gave us life after causing us to die (sleep) and to Him is the resurrection.',
                                    'reference' => 'Narrated by al-Bukhari and Muslim',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 1,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'دعاء الصباح',
                                    'text' => 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ',
                                    'reference' => 'مِن الأذكار النبوية',
                                ],
                                'en' => [
                                    'title' => 'Morning remembrance',
                                    'text' => 'We have reached the morning and at this very time all sovereignty belongs to Allah. All praise is for Allah. None has the right to be worshipped except Allah, alone, without partner, to Him belongs all sovereignty and praise and He is over all things omnipotent.',
                                    'reference' => 'From the Prophetic adhkar',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 2,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'رضا بالله رباً',
                                    'text' => 'رَضِيتُ بِاللَّهِ رَبًّا، وَبِالْإِسْلَامِ دِينًا، وَبِمُحَمَّدٍ صَلَّى اللَّهُ عَلَيْهِ وَسَلَّمَ نَبِيًّا',
                                    'reference' => 'رواه أبو داود والترمذي وحسّنه الألباني',
                                ],
                                'en' => [
                                    'title' => 'Contentment with Allah as Lord',
                                    'text' => 'I am pleased with Allah as Lord, with Islam as religion, and with Muhammad (peace and blessings be upon him) as Prophet.',
                                    'reference' => 'Narrated by Abu Dawud and al-Tirmidhi; graded hasan by al-Albani',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'daily-supplications',
                'sort_order' => 1,
                'content' => [
                    'default_locale' => $defaultLocale,
                    'translations' => [
                        'ar' => ['title' => 'أدعية يومية'],
                        'en' => ['title' => 'Daily supplications'],
                    ],
                ],
                'duas' => [
                    [
                        'sort_order' => 0,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'قبل الطعام',
                                    'text' => 'بِسْمِ اللَّهِ',
                                    'reference' => 'رواه أبو داود والترمذي',
                                ],
                                'en' => [
                                    'title' => 'Before eating',
                                    'text' => 'In the name of Allah.',
                                    'reference' => 'Narrated by Abu Dawud and al-Tirmidhi',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 1,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'عند دخول المسجد',
                                    'text' => 'اللَّهُمَّ افْتَحْ لِي أَبْوَابَ رَحْمَتِكَ',
                                    'reference' => 'رواه مسلم',
                                ],
                                'en' => [
                                    'title' => 'Entering the mosque',
                                    'text' => 'O Allah, open for me the doors of Your mercy.',
                                    'reference' => 'Narrated by Muslim',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 2,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'عند الخروج من المنزل',
                                    'text' => 'بِسْمِ اللَّهِ، تَوَكَّلْتُ عَلَى اللَّهِ، لَا حَوْلَ وَلَا قُوَّةَ إِلَّا بِاللَّهِ',
                                    'reference' => 'رواه أبو داود والترمذي',
                                ],
                                'en' => [
                                    'title' => 'Leaving home',
                                    'text' => 'In the name of Allah, I place my trust in Allah; there is no might nor power except with Allah.',
                                    'reference' => 'Narrated by Abu Dawud and al-Tirmidhi',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'hajj-umrah',
                'sort_order' => 2,
                'content' => [
                    'default_locale' => $defaultLocale,
                    'translations' => [
                        'ar' => ['title' => 'الحج والعمرة'],
                        'en' => ['title' => 'Hajj & Umrah'],
                    ],
                ],
                'duas' => [
                    [
                        'sort_order' => 0,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'التلبية',
                                    'text' => 'لَبَّيْكَ اللَّهُمَّ لَبَّيْكَ، لَبَّيْكَ لَا شَرِيكَ لَكَ لَبَّيْكَ، إِنَّ الْحَمْدَ وَالنِّعْمَةَ لَكَ وَالْمُلْكَ، لَا شَرِيكَ لَكَ',
                                    'reference' => 'مشروع في الحج والعمرة',
                                ],
                                'en' => [
                                    'title' => 'Talbiyah',
                                    'text' => 'Here I am, O Allah, here I am. Here I am, You have no partner, here I am. Verily all praise and blessings are Yours, and all sovereignty, You have no partner.',
                                    'reference' => 'Prescribed in Hajj and Umrah',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 1,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'عند رؤية الكعبة',
                                    'text' => 'اللَّهُمَّ زِدْ هَذَا الْبَيْتَ تَشْرِيفًا وَتَعْظِيمًا وَمَهَابَةً وَبَرَكَةً، وَزِدْ مَنْ شَرَّفَهُ وَكَرَّمَهُ مِمَّنْ حَجَّهُ أَوْ اعْتَمَرَهُ تَشْرِيفًا وَتَكْرِيمًا وَبِرًّا وَبَرَكَةً',
                                    'reference' => 'من الأدعية المأثورة عند أهل العلم',
                                ],
                                'en' => [
                                    'title' => 'Upon seeing the Kaaba',
                                    'text' => 'O Allah, increase this House in honour, greatness, awe, and blessing; and increase whoever honours and venerates it—whether for Hajj or Umrah—in honour, generosity, righteousness, and blessing.',
                                    'reference' => 'From transmitted supplications',
                                ],
                            ],
                        ],
                    ],
                    [
                        'sort_order' => 2,
                        'content' => [
                            'default_locale' => $defaultLocale,
                            'translations' => [
                                'ar' => [
                                    'title' => 'يوم عرفة',
                                    'text' => 'لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٌ',
                                    'reference' => 'من الذكر المأثور يوم عرفة',
                                ],
                                'en' => [
                                    'title' => 'Day of Arafah',
                                    'text' => 'There is no god but Allah alone, with no partner; His is the dominion, His is the praise, and He is over all things competent.',
                                    'reference' => 'From the remembrance of the Day of Arafah',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($sections as $sectionRow) {
            $duas = $sectionRow['duas'];
            unset($sectionRow['duas']);

            $sectionId = DB::table('manasik_dua_sections')->insertGetId([
                'slug' => $sectionRow['slug'],
                'sort_order' => $sectionRow['sort_order'],
                'status' => true,
                'content' => json_encode($sectionRow['content']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($duas as $dua) {
                DB::table('manasik_duas')->insert([
                    'manasik_dua_section_id' => $sectionId,
                    'sort_order' => $dua['sort_order'],
                    'status' => true,
                    'content' => json_encode($dua['content']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
