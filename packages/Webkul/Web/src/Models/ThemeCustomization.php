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

    public const WEB_HEADER = 'web_header';

    public const WEB_FOOTER = 'web_footer';

    /** Inner pages hero (not home). Single row per theme; configurable per locale in admin. */
    public const INNER_PAGE_HERO = 'inner_page_hero';
}
