<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Facades\Schema;
use Webkul\Manasik\Models\Dua;
use Webkul\Manasik\Models\HajjRite;
use Webkul\Manasik\Models\HajjUser;
use Webkul\Manasik\Models\MapLocation;
use Webkul\Manasik\Models\ManasikGlobalStat;

class ManasikDashboardStats
{
    /**
     * @return array{
     *     available: bool,
     *     guide_full_completions_total?: int,
     *     hajj_users_total?: int,
     *     hajj_users_with_completion?: int,
     *     hajj_users_completions_sum?: int,
     *     duas_total?: int,
     *     map_locations_total?: int,
     *     map_locations_active?: int,
     *     hajj_rites_active?: int
     * }
     */
    public function snapshot(): array
    {
        if (! Schema::hasTable('manasik_hajj_users')) {
            return ['available' => false];
        }

        $guideFullCompletionsTotal = 0;
        if (Schema::hasTable('manasik_global_stats')) {
            $row = ManasikGlobalStat::query()->find(1);
            $guideFullCompletionsTotal = (int) ($row?->guide_full_completions ?? 0);
        }

        $hajjUsersTotal = HajjUser::query()->count();

        $hajjUsersWithCompletion = 0;
        $hajjUsersCompletionsSum = 0;
        if (Schema::hasColumn('manasik_hajj_users', 'manasik_guide_completions_count')) {
            $hajjUsersWithCompletion = HajjUser::query()
                ->where('manasik_guide_completions_count', '>', 0)
                ->count();
            $hajjUsersCompletionsSum = (int) HajjUser::query()
                ->sum('manasik_guide_completions_count');
        }

        $duasTotal = 0;
        if (Schema::hasTable('manasik_duas')) {
            $duasTotal = (int) Dua::query()->count();
        }

        $mapLocationsTotal = 0;
        $mapLocationsActive = 0;
        if (Schema::hasTable('manasik_map_locations')) {
            $mapLocationsTotal = (int) MapLocation::query()->count();
            $mapLocationsActive = (int) MapLocation::query()->where('status', true)->count();
        }

        $hajjRitesActive = 0;
        if (Schema::hasTable('manasik_hajj_rites')) {
            $hajjRitesActive = (int) HajjRite::query()->where('status', true)->count();
        }

        return [
            'available'                      => true,
            'guide_full_completions_total'   => $guideFullCompletionsTotal,
            'hajj_users_total'               => $hajjUsersTotal,
            'hajj_users_with_completion'     => $hajjUsersWithCompletion,
            'hajj_users_completions_sum'     => $hajjUsersCompletionsSum,
            'duas_total'                     => $duasTotal,
            'map_locations_total'            => $mapLocationsTotal,
            'map_locations_active'           => $mapLocationsActive,
            'hajj_rites_active'              => $hajjRitesActive,
        ];
    }

    /**
     * Hajj accounts ranked by full guide completions (registered users only).
     *
     * @return list<array{name: string, email: string, completions: int}>
     */
    public function topUsersByCompletions(int $limit = 15): array
    {
        if (! Schema::hasTable('manasik_hajj_users')
            || ! Schema::hasColumn('manasik_hajj_users', 'manasik_guide_completions_count')) {
            return [];
        }

        return HajjUser::query()
            ->where('manasik_guide_completions_count', '>', 0)
            ->orderByDesc('manasik_guide_completions_count')
            ->orderBy('name')
            ->limit($limit)
            ->get(['name', 'email', 'manasik_guide_completions_count'])
            ->map(static fn (HajjUser $u): array => [
                'name'         => (string) $u->name,
                'email'        => (string) $u->email,
                'completions'  => (int) $u->manasik_guide_completions_count,
            ])
            ->values()
            ->all();
    }
}
