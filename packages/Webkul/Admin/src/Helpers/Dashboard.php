<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Carbon;

class Dashboard
{
    public function getEventsStudentsOverAllStats(): array
    {
        return [];
    }

    public function getStudentSubscriptionsOverTime(): array
    {
        return [];
    }

    public function getEventsStatusDistribution(): array
    {
        return [];
    }

    public function getTopSubscribedEvents(): array
    {
        return [];
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
