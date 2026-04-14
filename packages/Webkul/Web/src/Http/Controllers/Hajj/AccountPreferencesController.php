<?php

namespace Webkul\Web\Http\Controllers\Hajj;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webkul\Web\Http\Requests\Hajj\UpdateHajjPreferencesRequest;

class AccountPreferencesController extends Controller
{
    public function update(UpdateHajjPreferencesRequest $request): RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $validated = $request->validated();

        $user->locale = strtolower((string) $validated['locale']);

        $existing = $user->resolvedPreferences();
        $rawPrefs = is_array($user->preferences) ? $user->preferences : [];
        $user->preferences = array_merge($rawPrefs, [
            'notify_prayer' => $existing['notify_prayer'],
            'notify_hajj' => $existing['notify_hajj'],
            'notify_news' => $existing['notify_news'],
            'theme' => $existing['theme'],
        ]);
        $user->save();

        $request->session()->put('locale', $user->locale);
        $request->session()->put('web_theme', $existing['theme']);

        return redirect()
            ->route('hajj.account.index', ['tab' => 'preferences'])
            ->with('success', __('web::hajj_account.flash.preferences_updated'));
    }
}
