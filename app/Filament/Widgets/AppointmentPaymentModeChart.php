<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ResolvesDashboardDateRange;
use App\Filament\Widgets\Concerns\ResolvesScopedQueries;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class AppointmentPaymentModeChart extends ChartWidget
{
    use ResolvesDashboardDateRange;
    use ResolvesScopedQueries;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = '30';

    protected ?string $heading = 'طرق الدفع';

    protected ?string $description = 'نقدي مقابل أونلاين';

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

        $cacheKey = sprintf(
            'dashboard:appointments-payment-mode:%s:%s:%s',
            $user?->roleNormalized() ?? 'guest',
            $user?->id ?? 0,
            $days,
        );

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($range) {
            $counts = $this->getScopedAppointmentsQuery()
                ->whereBetween('date', [$range['start']->toDateString(), $range['end']->toDateString()])
                ->selectRaw('payment_mode, count(*) as aggregate')
                ->groupBy('payment_mode')
                ->pluck('aggregate', 'payment_mode')
                ->map(fn ($value) => (int) $value)
                ->all();

            $cash = $counts['cash'] ?? 0;
            $online = $counts['online'] ?? 0;

            return [
                'datasets' => [
                    [
                        'data' => [$cash, $online],
                        'backgroundColor' => ['#3b82f6', '#a855f7'],
                    ],
                ],
                'labels' => ['نقدي', 'أونلاين'],
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

