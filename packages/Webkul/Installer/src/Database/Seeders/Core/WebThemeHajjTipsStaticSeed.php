<?php

namespace Webkul\Installer\Database\Seeders\Core;

/**
 * Default STATIC_CONTENT home block: Hajj tips grid + CSS-only :target modals.
 * Markup is scoped under .web-home-hajj-tips; no document shell or external CDNs.
 */
final class WebThemeHajjTipsStaticSeed
{
    /**
     * @param  array{heading: string, subheading: string, read_more: string, got_it: string}  $labels
     * @return array{html: string, css: string}
     */
    public static function htmlAndCss(string $locale, array $labels): array
    {
        $isAr = str_starts_with(strtolower($locale), 'ar');
        $h = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $heading = $h($labels['heading']);
        $subheading = $h($labels['subheading']);
        $readMore = $h($labels['read_more']);
        $gotIt = $h($labels['got_it']);
        $aria = $h($labels['heading'] !== '' ? $labels['heading'] : ($isAr ? 'نصائح للحجاج' : 'Tips for pilgrims'));

        $arrowIcon = $isAr ? 'fa-arrow-left' : 'fa-arrow-right';
        $cards = $isAr ? self::cardsAr() : self::cardsEn();

        $shellOpen = '<section id="web-home-hajj-tips" class="web-supplications web-home-hajj-tips" aria-label="'.$aria.'">'
            .'<div class="web-supplications__inner">'
            .'<div class="web-home-hajj-tips__shell">'
            .'<div class="web-home-hajj-tips__intro">'
            .'<h2 class="web-home-hajj-tips__intro-title"><i class="fas fa-lightbulb" aria-hidden="true"></i> '.$heading.'</h2>'
            .'<p class="web-home-hajj-tips__intro-sub">'.$subheading.'</p>'
            .'</div>'
            .'<div class="web-home-hajj-tips__grid">';

        $grid = '';
        foreach ($cards as $i => $c) {
            $n = $i + 1;
            $mid = 'web-hajj-tip-m-'.$n;
            $grid .= '<div class="web-home-hajj-tips__tip">'
                .'<div class="web-home-hajj-tips__tip-icon"><i class="'.$h($c['icon']).'" aria-hidden="true"></i></div>'
                .'<h3 class="web-home-hajj-tips__tip-title">'.$h($c['title']).'</h3>'
                .'<p class="web-home-hajj-tips__tip-text">'.$h($c['preview']).'</p>'
                .'<a href="#'.$mid.'" class="web-home-hajj-tips__tip-link">'.$readMore.' <i class="fas '.$arrowIcon.'" aria-hidden="true"></i></a>'
                .'</div>';
        }

        $shellMid = '</div></div></div>';

        $modals = '';
        foreach ($cards as $i => $c) {
            $n = $i + 1;
            $mid = 'web-hajj-tip-m-'.$n;
            $lines = '';
            foreach ($c['lines'] as $line) {
                $lines .= '<p>• '.$h($line).'</p>';
            }
            $modals .= '<div id="'.$mid.'" class="web-home-hajj-tips__overlay" role="dialog" aria-modal="true">'
                .'<div class="web-home-hajj-tips__panel">'
                .'<div class="web-home-hajj-tips__panel-icon"><i class="'.$h($c['icon']).'" aria-hidden="true"></i></div>'
                .'<h3 class="web-home-hajj-tips__panel-title">'.$h($c['modalTitle']).'</h3>'
                .'<div class="web-home-hajj-tips__panel-body">'.$lines.'</div>'
                .'<a href="#web-home-hajj-tips" class="web-home-hajj-tips__panel-close">'.$gotIt.' <i class="fas fa-check" aria-hidden="true"></i></a>'
                .'</div></div>';
        }

        $html = $shellOpen.$grid.$shellMid.$modals.'</section>';

        return ['html' => $html, 'css' => self::css()];
    }

    /**
     * @return list<array{icon: string, title: string, preview: string, modalTitle: string, lines: list<string>}>
     */
    private static function cardsAr(): array
    {
        return [
            [
                'icon'        => 'fas fa-heartbeat',
                'title'       => 'الصحة والسلامة',
                'preview'     => 'احرص على شرب كميات كافية من الماء، وتجنب التعرض المباشر للشمس...',
                'modalTitle'  => 'نصائح الصحة والسلامة',
                'lines'       => [
                    'احرص على شرب كميات كافية من الماء يومياً لتجنب الجفاف.',
                    'استخدم مظلة شمسية للحماية من أشعة الشمس المباشرة.',
                    'احمل معك أدوية الطوارئ الشخصية (ضغط، سكر، حساسية).',
                    'ارتدِ الكمامة في الأماكن المزدحمة للوقاية من الأمراض.',
                    'خذ قسطاً كافياً من الراحة بين المناسك لتجنب الإرهاق.',
                    'احمل مطهراً لليدين واستخدمه باستمرار.',
                ],
            ],
            [
                'icon'        => 'fas fa-hands-praying',
                'title'       => 'أدعية الحج',
                'preview'     => 'تعلم أدعية الحج المأثورة عن النبي ﷺ واحرص على ترديدها في أوقاتها...',
                'modalTitle'  => 'أدعية الحج المستحبة',
                'lines'       => [
                    'دعاء التلبية: "لَبَّيْكَ اللَّهُمَّ لَبَّيْكَ، لَبَّيْكَ لا شَرِيكَ لَكَ لَبَّيْكَ..."',
                    'دعاء دخول المسجد الحرام: "اللهم افتح لي أبواب رحمتك".',
                    'دعاء الصفا والمروة: "إِنَّ الصَّفَا وَالْمَرْوَةَ مِن شَعَائِرِ اللَّهِ".',
                    'دعاء يوم عرفة: "لا إله إلا الله وحده لا شريك له..."',
                    'دعاء رمي الجمرات: "اللهم اجعله حجاً مبروراً وسعياً مشكوراً".',
                    'دعاء ختم الطواف: "اللهم إني أسألك رضاك والجنة".',
                ],
            ],
            [
                'icon'        => 'fas fa-tshirt',
                'title'       => 'ملابس الإحرام',
                'preview'     => 'تأكد من تجهيز ملابس الإحرام المناسبة، إزار ورداء أبيضين نظيفين...',
                'modalTitle'  => 'ملابس الإحرام',
                'lines'       => [
                    'للرجال: إزار ورداء أبيضان نظيفان غير مخيطين.',
                    'للنساء: ملابس ساترة للجسم مع كشف الوجه والكفين.',
                    'يُستحب الاغتسال والتطيب قبل الإحرام (بغير طيب).',
                    'تجنب محظورات الإحرام: قص الأظافر، تغطية الرأس للرجال، الطيب.',
                    'احمل معك ملابس إحرام احتياطية في حال اتساخها.',
                    'يفضل استخدام نعل مكشوف للرجال.',
                ],
            ],
            [
                'icon'        => 'fas fa-map-marked-alt',
                'title'       => 'التنقل والخرائط',
                'preview'     => 'حمل خرائط المشاعر المقدسة على هاتفك لتسهيل التنقل بين المناسك...',
                'modalTitle'  => 'خرائط المشاعر المقدسة',
                'lines'       => [
                    'حمل خريطة المسجد الحرام لتتعرف على مواقع الأبواب والمطاف.',
                    'استخدم خريطة منى لمعرفة مواقع مخيمات الجمرات.',
                    'تعرف على مسارات الحركة بين عرفة ومزدلفة ومنى.',
                    'استخدم تطبيقات الخرائط الذكية لتحديد موقعك.',
                    'احمل خريطة ورقية احتياطية في حال نفاد بطارية الهاتف.',
                    'تعرف على مواقع أقرب المخارج والطوارئ.',
                ],
            ],
            [
                'icon'        => 'fas fa-clock',
                'title'       => 'إدارة الوقت',
                'preview'     => 'نظم وقتك بين العبادات والراحة، وتجنب الازدحام في أوقات الذروة...',
                'modalTitle'  => 'إدارة الوقت في الحج',
                'lines'       => [
                    'خطط ليومك مسبقاً: حدد أوقات العبادة والراحة والطعام.',
                    'تجنب أوقات الذروة في الطواف والسعي والرمي.',
                    'استغل أوقات ما بعد الفجر وما قبل العشاء لقلة الازدحام.',
                    'لا تؤجل المناسك إلى آخر الوقت لتجنب الزحام.',
                    'احرص على أداء الصلوات في وقتها جماعة.',
                    'وزع طاقتك على أيام الحج بشكل متوازن.',
                ],
            ],
            [
                'icon'        => 'fas fa-phone-alt',
                'title'       => 'التواصل والدعم',
                'preview'     => 'احفظ أرقام الطوارئ والجهات المختصة، وتواصل مع فريق الدعم عند الحاجة...',
                'modalTitle'  => 'أرقام الطوارئ والدعم',
                'lines'       => [
                    'رقم الطوارئ الموحد في السعودية: 911',
                    'الهلال الأحمر السعودي: 997',
                    'الإسعاف في المشاعر المقدسة: 977',
                    'مركز التواصل مع البعثات: 920020405',
                    'احفظ أرقام سفارة بلدك في السعودية.',
                    'للدعم الفني للتطبيق أو البوابة: راجع القنوات الرسمية في حزمة الحاج.',
                ],
            ],
            [
                'icon'        => 'fas fa-suitcase',
                'title'       => 'الأغراض الأساسية',
                'preview'     => 'تجهيز حقيبة الحاج بالأساسيات: أدوية شخصية، أدوات النظافة، وثائق السفر...',
                'modalTitle'  => 'حقيبة الحاج الأساسية',
                'lines'       => [
                    'وثائق السفر: جواز السفر، تأشيرة الحج، بطاقات التعريف.',
                    'أدوية شخصية وعلاجات طارئة مع روشتة طبية.',
                    'أدوات النظافة الشخصية: فرشاة أسنان، مناديل معقمة.',
                    'ملابس إحرام إضافية ومطهرات.',
                    'حقيبة ظهر صغيرة تحمل معك أثناء المناسك.',
                    'شاحن متنقل (Power Bank) لهاتفك.',
                ],
            ],
            [
                'icon'        => 'fas fa-smile',
                'title'       => 'الروحانية والصبر',
                'preview'     => 'تذكر أن الحج رحلة روحانية، تحلى بالصبر والتسامح مع الآخرين...',
                'modalTitle'  => 'الروحانية والصبر',
                'lines'       => [
                    'تذكر أن الحج رحلة روحانية، اجعل قلبك معلقاً بالله.',
                    'تحلى بالصبر والتسامح مع الحجاج الآخرين.',
                    'أكثر من الذكر والدعاء والاستغفار.',
                    'استشعر عظمة الوقوف بين يدي الله في عرفة.',
                    'احرص على الدعاء لنفسك ولأهلك وللمسلمين.',
                    'اغتنم كل لحظة في الحج وتجنب المشاحنات.',
                ],
            ],
        ];
    }

    /**
     * @return list<array{icon: string, title: string, preview: string, modalTitle: string, lines: list<string>}>
     */
    private static function cardsEn(): array
    {
        return [
            [
                'icon'        => 'fas fa-heartbeat',
                'title'       => 'Health and safety',
                'preview'     => 'Stay hydrated, limit direct sun exposure, and keep personal medication handy...',
                'modalTitle'  => 'Health and safety tips',
                'lines'       => [
                    'Drink enough water every day to avoid dehydration.',
                    'Use a sun umbrella or hat (when not in ihram restrictions) for shade.',
                    'Carry personal emergency medicines (blood pressure, diabetes, allergies).',
                    'Wear a mask in very crowded spaces when appropriate.',
                    'Rest enough between rites to avoid exhaustion.',
                    'Carry hand sanitiser and use it regularly.',
                ],
            ],
            [
                'icon'        => 'fas fa-hands-praying',
                'title'       => 'Hajj supplications',
                'preview'     => 'Learn the Prophetic duas for Hajj and repeat them at the right moments...',
                'modalTitle'  => 'Recommended duas',
                'lines'       => [
                    'Talbiyah: “Here I am, O Allah, here I am…” (repeat with presence of heart).',
                    'Entering al-Masjid al-Haram: “O Allah, open for me the doors of Your mercy.”',
                    'Safa and Marwah: reflect on the verses about these rites of Allah.',
                    'Day of Arafah: seize the hour of acceptance with tawhid and heartfelt dua.',
                    'Stoning: ask Allah for an accepted Hajj and appreciated effort.',
                    'After tawaf: ask Allah for His pleasure and Paradise.',
                ],
            ],
            [
                'icon'        => 'fas fa-tshirt',
                'title'       => 'Ihram clothing',
                'preview'     => 'Prepare clean, suitable ihram: two white cloths for men; modest dress for women...',
                'modalTitle'  => 'Ihram clothing',
                'lines'       => [
                    'Men: two seamless white cloths (izar and rida), clean and plain.',
                    'Women: modest clothing covering the body; face and hands may remain uncovered per fiqh.',
                    'It is sunnah to bathe before ihram; scent before entering ihram, not after.',
                    'Avoid ihram prohibitions: cutting nails, men covering the head, wearing perfume, etc.',
                    'Pack a spare set of ihram in case it gets soiled.',
                    'Men often prefer open sandals that do not cover most of the foot.',
                ],
            ],
            [
                'icon'        => 'fas fa-map-marked-alt',
                'title'       => 'Maps and movement',
                'preview'     => 'Save maps of the holy sites on your phone to move between rites with confidence...',
                'modalTitle'  => 'Maps of the holy sites',
                'lines'       => [
                    'Keep a map of al-Masjid al-Haram for gates and tawaf flow.',
                    'Use a map of Mina for camps and Jamarat routes.',
                    'Learn the flow between Arafah, Muzdalifah, and Mina.',
                    'Use trusted map apps to orient yourself when signal allows.',
                    'Carry a paper backup if your phone battery fails.',
                    'Note nearest exits and emergency assembly points.',
                ],
            ],
            [
                'icon'        => 'fas fa-clock',
                'title'       => 'Time management',
                'preview'     => 'Balance worship and rest, and avoid peak crowding where you can...',
                'modalTitle'  => 'Managing time during Hajj',
                'lines'       => [
                    'Plan the day: worship, meals, sleep, and travel buffers.',
                    'Avoid peak times for tawaf, sa‘i, and stoning when possible.',
                    'After Fajr and before ‘Isha are often calmer for movement.',
                    'Do not delay rites until the last minutes of the window.',
                    'Pray in congregation in its time when you can.',
                    'Spread your energy across the days of Hajj.',
                ],
            ],
            [
                'icon'        => 'fas fa-phone-alt',
                'title'       => 'Contact and support',
                'preview'     => 'Save emergency numbers and your group’s contacts; ask official staff when unsure...',
                'modalTitle'  => 'Emergency numbers and support',
                'lines'       => [
                    'Saudi unified emergency: 911',
                    'Saudi Red Crescent: 997',
                    'Ambulance in the holy sites: 977',
                    'Mutawif / mission contact centre (example): 920020405 — confirm on official notices.',
                    'Save your embassy’s numbers in Saudi Arabia.',
                    'For app or portal support, use the official channels listed in your pilgrim pack.',
                ],
            ],
            [
                'icon'        => 'fas fa-suitcase',
                'title'       => 'Essentials to pack',
                'preview'     => 'Pack documents, personal medicine, hygiene items, and a small day bag for rites...',
                'modalTitle'  => 'The pilgrim’s core bag',
                'lines'       => [
                    'Travel documents: passport, Hajj visa, ID cards.',
                    'Personal medicines with prescriptions where required.',
                    'Hygiene kit: toothbrush, wipes, unscented soap when in ihram.',
                    'Spare ihram and small towel if needed.',
                    'A small backpack for day trips during the rites.',
                    'A reliable power bank for your phone.',
                ],
            ],
            [
                'icon'        => 'fas fa-smile',
                'title'       => 'Spirituality and patience',
                'preview'     => 'Hajj is a spiritual journey: be patient, forgive crowding, and keep your heart with Allah...',
                'modalTitle'  => 'Spirituality and patience',
                'lines'       => [
                    'Remember Hajj is worship of the heart as much as the limbs.',
                    'Be patient and gentle with other pilgrims.',
                    'Increase dhikr, dua, and istighfar throughout the days.',
                    'Feel the greatness of standing before Allah on Arafah.',
                    'Make dua for yourself, your family, and the Ummah.',
                    'Cherish every moment and avoid arguments and ill speech.',
                ],
            ],
        ];
    }

    private static function css(): string
    {
        return <<<'CSS'
.web-home-hajj-tips.web-supplications{background:linear-gradient(145deg,#fefaf5 0%,#f9f4ec 100%);border-radius:0}
.web-home-hajj-tips,.web-home-hajj-tips *{box-sizing:border-box}
.web-home-hajj-tips{font-family:'Cairo',var(--shop-font-sans),ui-sans-serif,system-ui,sans-serif}
.web-home-hajj-tips .web-home-hajj-tips__shell{max-width:1400px;margin:0 auto;padding-block:0.5rem;padding-inline:clamp(0.5rem,2vw,1rem)}
.web-home-hajj-tips .web-home-hajj-tips__intro{text-align:center;margin-bottom:2.5rem}
.web-home-hajj-tips .web-home-hajj-tips__intro-title{font-size:clamp(1.35rem,2.5vw,1.8rem);color:#1a3a2a;position:relative;display:inline-block;margin:0;font-weight:800}
.web-home-hajj-tips .web-home-hajj-tips__intro-title i{color:#d4af37;margin-inline-end:0.5rem}
.web-home-hajj-tips .web-home-hajj-tips__intro-title::after{content:'';position:absolute;bottom:-12px;left:50%;transform:translateX(-50%);width:60px;height:3px;background:linear-gradient(90deg,#d4af37,#f5c842);border-radius:3px}
.web-home-hajj-tips .web-home-hajj-tips__intro-sub{color:#7a8a7a;margin:1.25rem 0 0;font-size:0.9rem;line-height:1.65;max-width:36rem;margin-inline:auto}
.web-home-hajj-tips .web-home-hajj-tips__grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem}
.web-home-hajj-tips .web-home-hajj-tips__tip{background:#fff;border-radius:24px;padding:1.5rem;text-align:center;transition:all 0.3s ease;border:1px solid #eee6dc;position:relative;overflow:hidden}
.web-home-hajj-tips .web-home-hajj-tips__tip::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#d4af37,#f5c842);transform:scaleX(0);transition:transform 0.3s ease;transform-origin:center}
.web-home-hajj-tips .web-home-hajj-tips__tip:hover{transform:translateY(-8px);box-shadow:0 15px 35px rgba(0,0,0,0.1);border-color:#d4af37}
.web-home-hajj-tips .web-home-hajj-tips__tip:hover::before{transform:scaleX(1)}
.web-home-hajj-tips .web-home-hajj-tips__tip-icon{width:70px;height:70px;background:linear-gradient(135deg,#fef8f0,#fff5e8);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border:1px solid #f0e8de;transition:0.3s}
.web-home-hajj-tips .web-home-hajj-tips__tip:hover .web-home-hajj-tips__tip-icon{background:linear-gradient(135deg,#d4af37,#f5c842);border-color:#d4af37}
.web-home-hajj-tips .web-home-hajj-tips__tip-icon i{font-size:1.8rem;color:#d4af37;transition:0.3s}
.web-home-hajj-tips .web-home-hajj-tips__tip:hover .web-home-hajj-tips__tip-icon i{color:#1a3a2a}
.web-home-hajj-tips .web-home-hajj-tips__tip-title{font-size:1.1rem;font-weight:800;color:#1a3a2a;margin:0 0 0.8rem;line-height:1.35}
.web-home-hajj-tips .web-home-hajj-tips__tip-text{font-size:0.85rem;line-height:1.7;color:#5a6e5a;margin:0 0 1rem}
.web-home-hajj-tips .web-home-hajj-tips__tip-link{display:inline-flex;align-items:center;gap:0.4rem;background:none;border:1px solid #e8dfd0;padding:0.4rem 1rem;border-radius:50px;font-size:0.7rem;font-weight:600;color:#d4af37;cursor:pointer;transition:0.2s;font-family:inherit;text-decoration:none}
.web-home-hajj-tips .web-home-hajj-tips__tip-link:hover{background:#d4af37;color:#1a3a2a;border-color:#d4af37}
.web-home-hajj-tips .web-home-hajj-tips__overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);z-index:1000;visibility:hidden;opacity:0;transition:all 0.3s ease;display:flex;align-items:center;justify-content:center;padding:1rem}
.web-home-hajj-tips .web-home-hajj-tips__overlay:target{visibility:visible;opacity:1}
.web-home-hajj-tips .web-home-hajj-tips__panel{background:#fff;border-radius:32px;max-width:500px;width:100%;padding:2rem;position:relative;text-align:center;transform:scale(0.9);transition:transform 0.3s ease;max-height:min(90vh,640px);overflow:auto}
.web-home-hajj-tips .web-home-hajj-tips__overlay:target .web-home-hajj-tips__panel{transform:scale(1)}
.web-home-hajj-tips .web-home-hajj-tips__panel-icon{width:80px;height:80px;background:linear-gradient(135deg,#d4af37,#f5c842);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:-3rem auto 1rem auto}
.web-home-hajj-tips .web-home-hajj-tips__panel-icon i{font-size:2.5rem;color:#1a3a2a}
.web-home-hajj-tips .web-home-hajj-tips__panel-title{font-size:1.3rem;font-weight:800;color:#1a3a2a;margin:0 0 1rem;line-height:1.3}
.web-home-hajj-tips .web-home-hajj-tips__panel-body{font-size:0.95rem;line-height:1.8;color:#5a6e5a;margin-bottom:1.5rem;text-align:start}
.web-home-hajj-tips .web-home-hajj-tips__panel-body p{margin:0 0 0.5rem}
.web-home-hajj-tips .web-home-hajj-tips__panel-close{display:inline-flex;align-items:center;gap:0.5rem;background:linear-gradient(135deg,#d4af37,#f5c842);border:none;padding:0.6rem 1.5rem;border-radius:50px;font-size:0.85rem;font-weight:700;color:#1a3a2a;cursor:pointer;font-family:inherit;text-decoration:none}
.web-home-hajj-tips .web-home-hajj-tips__panel-close:hover{background:linear-gradient(135deg,#f5c842,#d4af37)}
@media (max-width:1100px){.web-home-hajj-tips .web-home-hajj-tips__grid{grid-template-columns:repeat(2,1fr);gap:1.2rem}}
@media (max-width:600px){.web-home-hajj-tips .web-home-hajj-tips__grid{grid-template-columns:1fr}.web-home-hajj-tips .web-home-hajj-tips__intro-title{font-size:1.35rem}}
CSS;
    }
}
