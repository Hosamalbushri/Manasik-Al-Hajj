<?php

namespace Webkul\Hajj\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Webkul\Hajj\Models\HajjUser;

class RegisterController extends Controller
{
    public function create(): RedirectResponse|View
    {
        if (auth()->guard('hajj')->check()) {
            return redirect()->route('hajj.account.index');
        }

        return view('hajj::register.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:191'],
            'email'    => ['required', 'email', 'max:191', 'unique:hajj_users,email'],
            'phone'    => ['nullable', 'string', 'max:32'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $phone = isset($validated['phone']) ? trim((string) $validated['phone']) : '';

        $user = HajjUser::query()->create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $phone !== '' ? $phone : null,
            'password' => $validated['password'],
            'status'   => true,
        ]);

        auth()->guard('hajj')->login($user);
        $request->session()->regenerate();

        return redirect()->route('hajj.account.index');
    }
}
