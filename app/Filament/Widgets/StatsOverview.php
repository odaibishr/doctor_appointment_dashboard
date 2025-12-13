<?php

namespace App\Filament\Widgets;

use App\Models\BookAppointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('عدد المرضى', Patient::count())
                ->description('إجمالي المرضى')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('عدد الأطباء', Doctor::count())
                ->description('إجمالي الأطباء')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('primary'),

            Stat::make('عدد المواعيد', BookAppointment::count())
                ->description('إجمالي المواعيد')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('إجمالي الإيرادات', number_format(Transaction::sum('amount'), 2))
                ->description('إجمالي المدفوعات')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
