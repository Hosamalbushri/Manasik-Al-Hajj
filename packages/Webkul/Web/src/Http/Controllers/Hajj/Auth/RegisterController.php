<?php

namespace Webkul\Web\Http\Controllers\Hajj\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Webkul\Manasik\Models\HajjUser;
use Webkul\Web\Http\Requests\Hajj\RegistrationRequest;
use Webkul\Web\Support\HajjAuthRegisterSettings;

class RegisterController extends Controller
{
    public function create(): RedirectResponse|View
    {
        if (Auth::guard('hajj')->check()) {
            return redirect()->route('web.home.index');
        }

        return view('web::hajj.auth.sign-up', ['active' => 'register']);
    }

    public function store(RegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $phone = isset($validated['phone']) ? trim((string) $validated['phone']) : '';

        $locale = strtolower((string) app()->getLocale());
        if (! core()->isStoreLocaleAllowed($locale)) {
            $opts = core()->storeLocales();
            $locale = isset($opts[0]['value']) ? strtolower((string) $opts[0]['value']) : $locale;
        }

        $preferences = null;
        if (
            HajjAuthRegisterSettings::newsletterSubscriptionEnabled()
            && $request->boolean('is_subscribed')
        ) {
            $preferences = ['notify_news' => true];
        }

        HajjUser::query()->create([
            'name' => trim($validated['name']),
            'email' => $validated['email'],
            'phone' => $phone !== '' ? $phone : null,
            'password' => $validated['password'],
            'status' => true,
            'locale' => core()->isStoreLocaleAllowed($locale) ? $locale : null,
            'preferences' => $preferences,
        ]);

        session()->flash('success', trans('web::hajj_auth.signup-form.success'));

        return redirect()->route('hajj.session.create');
    }
}
