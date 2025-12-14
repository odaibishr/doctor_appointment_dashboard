<?php

namespace App\Filament\Widgets\Concerns;

use Carbon\CarbonImmutable;

trait ResolvesDashboardDateRange
{
    protected function getSelectedDays(): int
    {
        $days = (int) ($this->filter ?? 30);

        return in_array($days, [7, 30, 90, 365], true) ? $days : 30;
    }

    /**
     * @return array{start: CarbonImmutable, end: CarbonImmutable}
     */
    protected function getDateRange(): array
    {
        $days = $this->getSelectedDays();

        $end = CarbonImmutable::today();
        $start = $end->subDays(max(0, $days - 1));

        return compact('start', 'end');
    }

    /**
     * @return array<string, string>
     */
    protected function getDateRangeFilters(): array
    {
        return [
            '7' => 'آخر 7 أيام',
            '30' => 'آخر 30 يوم',
            '90' => 'آخر 90 يوم',
            '365' => 'آخر سنة',
        ];
    }
}

