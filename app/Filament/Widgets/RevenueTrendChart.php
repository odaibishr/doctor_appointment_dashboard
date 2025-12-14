<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ResolvesDashboardDateRange;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RevenueTrendChart extends ChartWidget
{
    use ResolvesDashboardDateRange;

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = '30';

    protected ?string $heading = 'إيرادات مدفوعة';

    protected ?string $description = 'مجموع المدفوعات حسب اليوم';

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return $this->getDateRangeFilters();
    }

    protected function getData(): array
    {
        $days = $this->getSelectedDays();
        $range = $this->getDateRange();

        $lastUpdatedAt = (string) Transaction::query()
            ->where('status', 'paid')
            ->max('updated_at');

        $cacheKey = sprintf(
            'dashboard:revenue-trend:%s:%s',
            $days,
            $lastUpdatedAt !== '' ? $lastUpdatedAt : '0',
        );

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($range) {
            $totalsByDate = Transaction::query()
                ->where('status', 'paid')
                ->whereBetween('created_at', [$range['start']->startOfDay(), $range['end']->endOfDay()])
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date')
                ->map(fn ($value) => (float) $value)
                ->all();

            $labels = [];
            $values = [];

            $cursor = $range['start'];
            while ($cursor->lessThanOrEqualTo($range['end'])) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('m/d');
                $values[] = $totalsByDate[$key] ?? 0.0;
                $cursor = $cursor->addDay();
            }

            return [
                'datasets' => [
                    [
                        'label' => 'مدفوعات',
                        'data' => $values,
                        'borderWidth' => 1,
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
                ],
            ],
        ];
    }
}
