<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ResolvesDashboardDateRange;
use App\Models\BookAppointment;
use App\Models\Doctor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TopDoctorsChart extends ChartWidget
{
    use ResolvesDashboardDateRange;

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = '30';

    protected ?string $heading = 'أفضل الأطباء (حجوزات)';

    protected ?string $description = 'أعلى 5 حسب عدد الحجوزات';

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

        $lastUpdatedAt = (string) BookAppointment::query()->max('updated_at');

        $cacheKey = sprintf(
            'dashboard:top-doctors:%s:%s',
            $days,
            $lastUpdatedAt !== '' ? $lastUpdatedAt : '0',
        );

        return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($range) {
            $rows = Doctor::query()
                ->withCount([
                    'appointments as appointments_count' => fn ($query) => $query->whereBetween(
                        'date',
                        [$range['start']->toDateString(), $range['end']->toDateString()],
                    ),
                ])
                ->orderByDesc('appointments_count')
                ->limit(5)
                ->get(['id', 'name']);

            return [
                'datasets' => [
                    [
                        'label' => 'الحجوزات',
                        'data' => $rows->pluck('appointments_count')->map(fn ($v) => (int) $v)->all(),
                    ],
                ],
                'labels' => $rows->pluck('name')->all(),
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
