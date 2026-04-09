<?php

namespace Webkul\Web\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Visitor locale for public web routes (separate from admin session locale).
 */
class WebLocale
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionLocale = session('locale');
        if (is_string($sessionLocale)) {
            $sessionLocale = strtolower($sessionLocale);
        }
        if (is_string($sessionLocale) && core()->isStoreLocaleAllowed($sessionLocale)) {
            app()->setLocale($sessionLocale);
        } else {
            $configured = core()->getConfigData('general.general.locale_settings.locale');
            if (is_string($configured)) {
                $configured = strtolower($configured);
            }
            if (is_string($configured) && core()->isStoreLocaleAllowed($configured)) {
                app()->setLocale($configured);
            }
        }

        return $next($request);
    }
}
