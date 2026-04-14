<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Helpers\ManasikDashboardStats;

class DashboardController extends Controller
{
    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'manasik-over-all' => 'getManasikOverAllStats',
        'manasik-charts'   => 'getManasikChartsStats',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Dashboard $dashboardHelper,
        protected ManasikDashboardStats $manasikDashboardStats
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $snap = $this->manasikDashboardStats->snapshot();

        return view('admin::dashboard.index')->with([
            'manasikModuleAvailable' => $snap['available'],
            'topHajjUsers'           => $snap['available']
                ? $this->manasikDashboardStats->topUsersByCompletions(15)
                : [],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function stats()
    {
        $type = request()->query('type');

        if (! isset($this->typeFunctions[$type])) {
            return response()->json([
                'statistics' => [],
                'date_range' => $this->dashboardHelper->getDateRange(),
            ], 422);
        }

        $stats = $this->dashboardHelper->{$this->typeFunctions[$type]}();

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->dashboardHelper->getDateRange(),
        ]);
    }
}
