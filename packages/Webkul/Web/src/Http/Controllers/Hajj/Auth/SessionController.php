<?php

namespace Webkul\Web\Http\Controllers\Hajj\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Webkul\Web\Http\Requests\Hajj\LoginRequest;

class SessionController extends Controller
{
    public function create(Request $request): RedirectResponse|View
    {
        if (Auth::guard('hajj')->check()) {
            return redirect()->route('web.home.index');
        }

        return view('web::hajj.auth.sign-in', [
            'active' => 'login',
            'loginRedirect' => $this->safeRedirectPath($request->query('redirect')),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $remember = $request->boolean('remember');

        if (! Auth::guard('hajj')->attempt($request->only(['email', 'password']), $remember)) {
            session()->flash('error', trans('web::hajj_auth.login-form.invalid-credentials'));

            return redirect()->back()->withInput($request->only('email'));
        }

        $user = Auth::guard('hajj')->user();
        if ($user && ! $user->isActive()) {
            Auth::guard('hajj')->logout();

            session()->flash('warning', trans('web::hajj_auth.login-form.not-activated'));

            return redirect()->back()->withInput($request->only('email'));
        }

        if ($user) {
            $ul = is_string($user->locale) ? strtolower($user->locale) : '';
            if ($ul !== '' && core()->isStoreLocaleAllowed($ul)) {
                $request->session()->put('locale', $ul);
            }
            $request->session()->put('web_theme', $user->resolvedPreferences()['theme']);
        }

        $request->session()->regenerate();

        $request->session()->flash('success', trans('web::hajj_auth.login-form.login-success'));

        $to = $this->safeRedirectPath($request->input('redirect'));
        if ($to !== null) {
            return redirect()->to($to);
        }

        return redirect()->route('web.home.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('hajj')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('hajj.session.create')
            ->with('success', trans('web::hajj_auth.login-form.logout-success'));
    }

    private function safeRedirectPath(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $value = trim($value);
        if ($value === '' || strlen($value) > 512) {
            return null;
        }
        if (! str_starts_with($value, '/') || str_starts_with($value, '//')) {
            return null;
        }

        return $value;
    }
}
