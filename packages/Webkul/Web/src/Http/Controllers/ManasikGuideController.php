<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webkul\Manasik\Repositories\HajjRiteRepository;

class ManasikGuideController extends Controller
{
    public function index(HajjRiteRepository $hajjRiteRepository): View
    {
        $steps = $hajjRiteRepository->getGuideStepsForLocale(app()->getLocale());
        if ($steps === []) {
            $steps = trans('web::app.manasik.steps');
            if (! is_array($steps)) {
                $steps = [];
            }
        }

        $hajjLoggedIn = Auth::guard('hajj')->check();
        $manasikServerProgress = null;
        $saveManasikProgressUrl = null;
        $guestManasikCompletionUrl = $hajjLoggedIn ? null : route('web.manasik.guest_completion');

        if ($hajjLoggedIn) {
            $saveManasikProgressUrl = route('hajj.manasik.progress');
            $user = Auth::guard('hajj')->user();
            $prefs = is_array($user->preferences) ? $user->preferences : [];
            $g = $prefs['manasik_guide'] ?? null;
            if (is_array($g) && isset($g['completed'], $g['active_index']) && is_array($g['completed'])) {
                $n = count($steps);
                if (count($g['completed']) === $n && $n > 0) {
                    $manasikServerProgress = [
                        'completed' => array_map(static fn ($v): bool => (bool) $v, $g['completed']),
                        'active_index' => (int) $g['active_index'],
                    ];
                }
            }
        }

        return view('web::manasik.index', [
            'pageTitle' => __('web::app.manasik.meta_title'),
            'steps' => $steps,
            'hajjLoggedIn' => $hajjLoggedIn,
            'manasikServerProgress' => $manasikServerProgress,
            'saveManasikProgressUrl' => $saveManasikProgressUrl,
            'guestManasikCompletionUrl' => $guestManasikCompletionUrl,
        ]);
    }
}
