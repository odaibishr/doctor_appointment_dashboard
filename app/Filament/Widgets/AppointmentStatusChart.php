<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ResolvesDashboardDateRange;
use App\Filament\Widgets\Concerns\ResolvesScopedQueries;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class AppointmentStatusChart extends ChartWidget
{
    use ResolvesDashboardDateRange;
    use ResolvesScopedQueries;

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = '30';

    protected ?string $heading = 'توزيع الحالات';

    protected ?string $description = 'حسب حالة الحجز';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): ?array
    {
        return $this->getDateRangeFilters();
    }

    protected function getData(): array
    {
        $user = $this->getAuthUser();
        $days = $this->getSelectedDays();
        $range = $this->getDateRange();

        $lastUpdatedAt = (string) (clone $this->getScopedAppointmentsQuery())->max('updated_at');

        $cacheKey = sprintf(
            'dashboard:appointments-status:%s:%s:%s:%s',
            $user?->roleNormalized() ?? 'guest',
            $user?->id ?? 0,
            $days,
            $lastUpdatedAt !== '' ? $lastUpdatedAt : '0',
        );

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($range) {
            $counts = $this->getScopedAppointmentsQuery()
                ->whereBetween('date', [$range['start']->toDateString(), $range['end']->toDateString()])
                ->selectRaw('status, count(*) as aggregate')
                ->groupBy('status')
                ->pluck('aggregate', 'status')
                ->map(fn ($value) => (int) $value)
                ->all();

            $pending = $counts['pending'] ?? 0;
            $confirmed = $counts['confirmed'] ?? 0;
            $cancelled = $counts['cancelled'] ?? 0;

            return [
                'datasets' => [
                    [
                        'data' => [$pending, $confirmed, $cancelled],
                        'backgroundColor' => ['#f59e0b', '#22c55e', '#ef4444'],
                    ],
                ],
                'labels' => ['قيد الانتظار', 'مؤكد', 'ملغي'],
            ];
        });
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
