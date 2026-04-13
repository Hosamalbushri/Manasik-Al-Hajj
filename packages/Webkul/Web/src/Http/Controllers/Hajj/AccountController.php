<?php

namespace Webkul\Web\Http\Controllers\Hajj;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Webkul\Manasik\Repositories\DuaRepository;
use Webkul\Web\Http\Requests\Hajj\DestroyHajjAccountRequest;
use Webkul\Web\Http\Requests\Hajj\UpdateHajjPasswordRequest;
use Webkul\Web\Http\Requests\Hajj\UpdateHajjProfileRequest;

class AccountController extends Controller
{
    public function index(DuaRepository $duaRepository): View
    {
        $user = Auth::guard('hajj')->user();
        $locale = strtolower((string) app()->getLocale());

        $favoriteDuas = $user
            ->favoriteDuas()
            ->orderByPivot('created_at', 'desc')
            ->get()
            ->map(function ($dua) use ($duaRepository, $locale) {
                $content = is_array($dua->content) ? $dua->content : [];
                $d = $duaRepository->resolveDuaFields($content, $locale);

                return [
                    'id' => (int) $dua->id,
                    'title' => $d['title'],
                    'text' => $d['text'],
                    'reference' => $d['reference'],
                ];
            })
            ->all();

        $favoriteDuaIds = array_column($favoriteDuas, 'id');

        return view('web::hajj.account.index', [
            'hajjUser' => $user,
            'favoriteDuas' => $favoriteDuas,
            'favoriteDuaIds' => $favoriteDuaIds,
            'storeLocaleOptions' => core()->storeLocales(),
        ]);
    }

    public function updateProfile(UpdateHajjProfileRequest $request): RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $user->fill($request->validated());
        $user->save();

        return redirect()
            ->route('hajj.account.index')
            ->with('success', __('web::hajj_account.flash.profile_updated'));
    }

    public function updatePassword(UpdateHajjPasswordRequest $request): RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $user->password = $request->validated('password');
        $user->save();

        return redirect()
            ->route('hajj.account.index')
            ->with('success', __('web::hajj_account.flash.password_updated'))
            ->with('open_tab', 'security');
    }

    public function destroy(DestroyHajjAccountRequest $request): RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $user->delete();

        Auth::guard('hajj')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('web.home.index')
            ->with('success', __('web::hajj_account.flash.account_deleted'));
    }
}
