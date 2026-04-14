<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Webkul\Manasik\Models\ManasikGlobalStat;
use Webkul\Manasik\Repositories\HajjRiteRepository;
use Webkul\Manasik\Support\ManasikGuideCompletion;
use Webkul\Web\Http\Requests\RecordManasikGuestCompletionRequest;

class ManasikGuestCompletionController extends Controller
{
    public function store(
        RecordManasikGuestCompletionRequest $request,
        HajjRiteRepository $hajjRiteRepository
    ): JsonResponse {
        if (Auth::guard('hajj')->check()) {
            return response()->json(['ok' => true]);
        }

        $expected = $hajjRiteRepository->getGuideStepCount(app()->getLocale());
        if ((int) $request->input('step_count') !== $expected) {
            return response()->json([
                'message' => __('web::app.manasik.progress_step_mismatch'),
            ], 422);
        }

        $completed = array_map(static fn ($v): bool => (bool) $v, $request->input('completed', []));
        if (! ManasikGuideCompletion::allCompleted($completed, $expected)) {
            return response()->json([
                'message' => __('web::app.manasik.guest_completion_invalid'),
            ], 422);
        }

        DB::transaction(static function (): void {
            ManasikGlobalStat::incrementGuideFullCompletions();
        });

        return response()->json(['ok' => true]);
    }
}
