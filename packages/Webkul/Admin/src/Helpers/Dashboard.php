<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Carbon;

class Dashboard
{
    public function __construct(
        protected ManasikDashboardStats $manasikDashboardStats
    ) {}

    /**
     * Manasik / Hajj guide counters (not filtered by dashboard date range).
     *
     * @return array<string, array{current: int, progress: float}|string>
     */
    public function getManasikOverAllStats(): array
    {
        $snap = $this->manasikDashboardStats->snapshot();

        if (! ($snap['available'] ?? false)) {
            return ['manasik_module' => 'off'];
        }

        $cell = static fn (int $v): array => ['current' => $v, 'progress' => 0.0];

        return [
            'hajj_rites_active'            => $cell($snap['hajj_rites_active']),
            'duas_total'                   => $cell($snap['duas_total']),
            'map_locations_total'          => $cell($snap['map_locations_total']),
            'hajj_users_total'             => $cell($snap['hajj_users_total']),
            'hajj_users_with_completion'   => $cell($snap['hajj_users_with_completion']),
            'hajj_users_completions_sum'   => $cell($snap['hajj_users_completions_sum']),
            'guide_full_completions'       => $cell($snap['guide_full_completions_total']),
        ];
    }

    /**
     * Chart-friendly aggregates for the Manasik dashboard (not date-filtered).
     *
     * @return array<string, mixed>
     */
    public function getManasikChartsStats(): array
    {
        $snap = $this->manasikDashboardStats->snapshot();

        if (! ($snap['available'] ?? false)) {
            return ['manasik_module' => 'off'];
        }

        $mapTotal = (int) ($snap['map_locations_total'] ?? 0);
        $mapActive = (int) ($snap['map_locations_active'] ?? 0);
        $mapInactive = max(0, $mapTotal - $mapActive);

        return [
            'overview_bar' => [
                'labels' => [
                    __('admin::app.dashboard.index.manasik.chart-label-rites'),
                    __('admin::app.dashboard.index.manasik.chart-label-duas'),
                    __('admin::app.dashboard.index.manasik.chart-label-map'),
                    __('admin::app.dashboard.index.manasik.chart-label-hajj-users'),
                    __('admin::app.dashboard.index.manasik.chart-label-guide-completions'),
                ],
                'values' => [
                    (int) ($snap['hajj_rites_active'] ?? 0),
                    (int) ($snap['duas_total'] ?? 0),
                    $mapTotal,
                    (int) ($snap['hajj_users_total'] ?? 0),
                    (int) ($snap['guide_full_completions_total'] ?? 0),
                ],
            ],
            'map_doughnut' => [
                'labels' => [
                    __('admin::app.dashboard.index.manasik.map-active'),
                    __('admin::app.dashboard.index.manasik.map-inactive'),
                ],
                'values' => [$mapActive, $mapInactive],
            ],
        ];
    }

    public function getStartDate(): Carbon
    {
        $start = request()->query('start');

        if (! $start) {
            return now()->subDays(29)->startOfDay();
        }

        return Carbon::parse($start)->startOfDay();
    }

    public function getEndDate(): Carbon
    {
        $end = request()->query('end');

        if (! $end) {
            return now()->endOfDay();
        }

        return Carbon::parse($end)->endOfDay();
    }

    public function getDateRange(): string
    {
        return $this->getStartDate()->format('d M').' - '.$this->getEndDate()->format('d M');
    }
}
