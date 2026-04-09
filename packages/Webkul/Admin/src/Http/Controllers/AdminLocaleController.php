<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class AdminLocaleController extends Controller
{
    /**
     * Persist admin UI language in session (separate from storefront session locale).
     */
    public function switch(string $locale_code): RedirectResponse
    {
        $locale_code = strtolower($locale_code);

        if (! core()->isAdminLocaleAllowed($locale_code)) {
            abort(404);
        }

        session(['admin_locale' => $locale_code]);

        return redirect()->back();
    }
}
