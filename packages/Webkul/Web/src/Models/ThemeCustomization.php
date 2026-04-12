<?php

namespace Webkul\Web\Models;

/**
 * Theme customization type constants. Home sections use config('web.home_customizations').
 */
class ThemeCustomization
{
    public const IMAGE_CAROUSEL = 'image_carousel';

    public const STATIC_CONTENT = 'static_content';

    public const IMMERSIVE_HERO = 'immersive_hero';

    /** Home: curated supplications (duʿāʾ), editable per locale in Web theme. */
    public const SUPPLICATIONS_CONTENT = 'supplications_content';

    public const WEB_HEADER = 'web_header';

    public const WEB_FOOTER = 'web_footer';

    /** Inner pages hero (not home). Single row per theme; configurable per locale in admin. */
    public const INNER_PAGE_HERO = 'inner_page_hero';

    /** Home: decorative band matching inner-page hero (gradient + wave), optional text/buttons. */
    public const SECTION_DIVIDER = 'section_divider';

    /** Home: same map location cards as the maps page (data from Web map locations). */
    public const MAPS_SHOWCASE = 'maps_showcase';

    /** Home: prayer times card with Swiper + Aladhan API (timings by city). */
    public const PRAYER_TIMES = 'prayer_times';
}
