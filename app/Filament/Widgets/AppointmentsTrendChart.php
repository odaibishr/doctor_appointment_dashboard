<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ResolvesDashboardDateRange;
use App\Filament\Widgets\Concerns\ResolvesScopedQueries;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class AppointmentsTrendChart extends ChartWidget
{
    use ResolvesDashboardDateRange;
    use ResolvesScopedQueries;

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '30';

    protected ?string $heading = 'اتجاه الحجوزات';

    protected ?string $description = 'عدد الحجوزات حسب اليوم';

    protected function getType(): string
    {
        return 'line';
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
            'dashboard:appointments-trend:%s:%s:%s:%s',
            $user?->roleNormalized() ?? 'guest',
            $user?->id ?? 0,
            $days,
            $lastUpdatedAt !== '' ? $lastUpdatedAt : '0',
        );

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($range) {
            $countsByDate = $this->getScopedAppointmentsQuery()
                ->whereRaw('DATE(date) BETWEEN ? AND ?', [$range['start']->toDateString(), $range['end']->toDateString()])
                ->selectRaw('DATE(date) as date_key, count(*) as aggregate')
                ->groupBy('date_key')
                ->orderBy('date_key')
                ->pluck('aggregate', 'date_key')
                ->map(fn ($value) => (int) $value)
                ->all();

            $labels = [];
            $values = [];

            $cursor = $range['start'];
            while ($cursor->lessThanOrEqualTo($range['end'])) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('m/d');
                $values[] = $countsByDate[$key] ?? 0;
                $cursor = $cursor->addDay();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'الحجوزات',
                        'data' => $values,
                        'borderWidth' => 2,
                        'fill' => 'start',
                        'tension' => 0.3,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
