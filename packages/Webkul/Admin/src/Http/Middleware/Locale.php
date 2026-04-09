<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Admin panel UI language (session admin_locale + configuration).
 * Independent of storefront session('locale').
 */
class Locale
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('admin_locale');

        if (! is_string($locale) || ! core()->isAdminLocaleAllowed($locale)) {
            $locale = core()->getConfigData('general.general.admin_locale_settings.locale');
        }

        if (! is_string($locale) || ! core()->isAdminLocaleAllowed($locale)) {
            $locale = core()->getConfigData('general.general.locale_settings.locale');
        }

        if (! is_string($locale) || ! core()->isAdminLocaleAllowed($locale)) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
