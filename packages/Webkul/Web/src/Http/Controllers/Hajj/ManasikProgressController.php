<?php

namespace Webkul\Web\Http\Controllers\Hajj;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Manasik\Models\HajjUser;
use Webkul\Manasik\Models\ManasikGlobalStat;
use Webkul\Manasik\Repositories\HajjRiteRepository;
use Webkul\Manasik\Support\ManasikGuideCompletion;
use Webkul\Web\Http\Requests\Hajj\SaveManasikProgressRequest;

class ManasikProgressController extends Controller
{
    public function update(
        SaveManasikProgressRequest $request,
        HajjRiteRepository $hajjRiteRepository
    ): JsonResponse {
        $expected = $hajjRiteRepository->getGuideStepCount(app()->getLocale());
        if ((int) $request->input('step_count') !== $expected) {
            return response()->json([
                'message' => __('web::app.manasik.progress_step_mismatch'),
            ], 422);
        }

        $userId = Auth::guard('hajj')->id();
        $newCompleted = array_map(static fn ($v): bool => (bool) $v, $request->input('completed', []));

        DB::transaction(function () use ($userId, $request, $expected, $newCompleted): void {
            $user = HajjUser::query()->lockForUpdate()->findOrFail($userId);
            $prefs = is_array($user->preferences) ? $user->preferences : [];
            $oldGuide = $prefs['manasik_guide'] ?? null;
            $oldCompleted = is_array($oldGuide['completed'] ?? null)
                ? array_map(static fn ($v): bool => (bool) $v, $oldGuide['completed'])
                : [];

            if (ManasikGuideCompletion::shouldRecordNewFullCompletion($oldCompleted, $newCompleted, $expected)) {
                ManasikGlobalStat::incrementGuideFullCompletions();
                if (Schema::hasColumn('manasik_hajj_users', 'manasik_guide_completions_count')) {
                    $user->increment('manasik_guide_completions_count');
                }
            }

            $prefs['manasik_guide'] = [
                'completed'    => $newCompleted,
                'active_index' => (int) $request->input('active_index'),
                'updated_at'   => now()->toIso8601String(),
            ];
            $user->preferences = $prefs;
            $user->save();
        });

        return response()->json(['ok' => true]);
    }
}
