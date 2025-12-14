<?php

namespace App\Filament\Widgets;

use App\Models\BookAppointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $today = Carbon::today();
        $monthStart = now()->startOfMonth();

        $appointmentsQuery = BookAppointment::query()
            ->when(
                $user->isDoctor(),
                fn(Builder $query) => $query->where('doctor_id', $user->doctor?->id),
            )
            ->when(
                $user->isPatient(),
                fn(Builder $query) => $query->where('user_id', $user->id),
            );

        if ($user->isAdmin()) {
            return [
                Stat::make('إجمالي المرضى', Patient::count())
                    ->description('عدد المرضى المسجّلين')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success'),

                Stat::make('إجمالي الأطباء', Doctor::count())
                    ->description('عدد الأطباء المسجّلين')
                    ->descriptionIcon('heroicon-m-user-circle')
                    ->color('primary'),

                Stat::make('حجوزات اليوم', (clone $appointmentsQuery)->whereDate('date', $today)->count())
                    ->description('عدد الحجوزات في تاريخ اليوم')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('warning'),

                Stat::make(
                    'إيرادات الشهر (مدفوعة)',
                    number_format(
                        Transaction::query()
                            ->where('status', 'paid')
                            ->whereBetween('created_at', [$monthStart, now()])
                            ->sum('amount'),
                        2,
                    ),
                )
                    ->description('إجمالي المدفوعات الشهر')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('info'),
            ];
        }

        if ($user->isDoctor()) {
            $doctorId = $user->doctor?->id;

            return [
                Stat::make(
                    'حجوزات اليوم',
                    $doctorId ? (clone $appointmentsQuery)->whereDate('date', $today)->count() : 0,
                )
                    ->description('الحجوزات الخاصة بك اليوم')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('warning'),

                Stat::make('قيد الانتظار', (clone $appointmentsQuery)->where('status', 'pending')->count())
                    ->description('حجوزات بحاجة لتأكيد')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('primary'),

                Stat::make('منتهية', (clone $appointmentsQuery)->where('is_completed', true)->count())
                    ->description('الحجوزات المكتملة')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),

                Stat::make(
                    'متوسط التقييم',
                    number_format(
                        Review::query()
                            ->where('doctor_id', $doctorId)
                            ->avg('rating') ?? 0,
                        1,
                    ),
                )
                    ->description('متوسط تقييمات المرضى')
                    ->descriptionIcon('heroicon-m-star')
                    ->color('info'),
            ];
        }

        return [
            Stat::make('مواعيد قادمة', (clone $appointmentsQuery)->whereDate('date', '>=', $today)->count())
                ->description('الحجوزات القادمة')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('إجمالي الحجوزات', (clone $appointmentsQuery)->count())
                ->description('كل حجوزاتك')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning'),

            Stat::make('الحجوزات الملغاة', (clone $appointmentsQuery)->where('status', 'cancelled')->count())
                ->description('الملغاة من إجمالي الحجوزات')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('إشعاراتك', $user->notifications()->count())
                ->description('عدد الإشعارات المسجّلة')
                ->descriptionIcon('heroicon-m-bell')
                ->color('info'),
        ];
    }
}

