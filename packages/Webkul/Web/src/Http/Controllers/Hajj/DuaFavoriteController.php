<?php

namespace Webkul\Web\Http\Controllers\Hajj;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webkul\Manasik\Models\Dua;
use Webkul\Web\Http\Requests\Hajj\ToggleHajjDuaFavoriteRequest;

class DuaFavoriteController extends Controller
{
    public function toggle(ToggleHajjDuaFavoriteRequest $request): JsonResponse|RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $duaId = (int) $request->validated('dua_id');

        if ($user->favoriteDuas()->where('manasik_duas.id', $duaId)->exists()) {
            $user->favoriteDuas()->detach($duaId);
            $saved = false;
        } else {
            $user->favoriteDuas()->syncWithoutDetaching([$duaId]);
            $saved = true;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['saved' => $saved, 'dua_id' => $duaId]);
        }

        return redirect()->back();
    }

    public function destroy(Dua $dua): RedirectResponse
    {
        $user = Auth::guard('hajj')->user();
        $user->favoriteDuas()->detach($dua->getKey());

        return redirect()
            ->route('hajj.account.index', ['tab' => 'favorites'])
            ->with('success', __('web::hajj_account.flash.favorite_removed'));
    }

    public function clear(): RedirectResponse
    {
        Auth::guard('hajj')->user()->favoriteDuas()->detach();

        return redirect()
            ->route('hajj.account.index', ['tab' => 'favorites'])
            ->with('success', __('web::hajj_account.flash.favorites_cleared'));
    }
}
