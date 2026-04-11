<?php

namespace Webkul\Hajj\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function create(): RedirectResponse|View
    {
        if (auth()->guard('hajj')->check()) {
            return redirect()->route('hajj.account.index');
        }

        return view('hajj::sessions.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! auth()->guard('hajj')->attempt($request->only('email', 'password'), $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans('hajj::session.failed')]);
        }

        $request->session()->regenerate();

        $user = auth()->guard('hajj')->user();
        if ($user && ! $user->isActive()) {
            auth()->guard('hajj')->logout();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans('hajj::session.inactive')]);
        }

        return redirect()->intended(route('hajj.account.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('hajj')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hajj.session.create');
    }
}
