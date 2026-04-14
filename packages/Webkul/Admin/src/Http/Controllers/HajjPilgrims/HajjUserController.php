<?php

namespace Webkul\Admin\Http\Controllers\HajjPilgrims;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\HajjPilgrims\HajjUserDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Manasik\Models\HajjUser;

class HajjUserController extends Controller
{
    protected function assertCanView(): void
    {
        if (! bouncer()->hasPermission('hajj_pilgrims')) {
            abort(403);
        }
    }

    public function index(): View|JsonResponse
    {
        $this->assertCanView();

        if (request()->ajax()) {
            return datagrid(HajjUserDataGrid::class)->process();
        }

        return view('admin::hajj-pilgrims.index');
    }

    public function show(int $id): View
    {
        $this->assertCanView();

        if (! Schema::hasTable('manasik_hajj_users')) {
            abort(404);
        }

        $hajjUser = HajjUser::query()->findOrFail($id);

        $favoritesCount = 0;
        if (Schema::hasTable('manasik_hajj_user_dua_favorites')) {
            $favoritesCount = $hajjUser->favoriteDuas()->count();
        }

        $completionsCount = 0;
        if (Schema::hasColumn('manasik_hajj_users', 'manasik_guide_completions_count')) {
            $completionsCount = (int) $hajjUser->manasik_guide_completions_count;
        }

        return view('admin::hajj-pilgrims.show', [
            'hajjUser'         => $hajjUser,
            'favoritesCount'   => $favoritesCount,
            'completionsCount' => $completionsCount,
        ]);
    }
}
