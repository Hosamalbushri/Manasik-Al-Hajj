<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class LocaleController extends Controller
{
    public function switch(string $locale_code): RedirectResponse
    {
        $locale_code = strtolower($locale_code);

        if (! core()->isStoreLocaleAllowed($locale_code)) {
            abort(404);
        }

        session(['locale' => $locale_code]);

        return redirect()->back();
    }
}
