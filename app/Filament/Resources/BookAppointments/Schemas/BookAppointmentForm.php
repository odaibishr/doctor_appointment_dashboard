<?php

namespace App\Filament\Resources\BookAppointments\Schemas;

use App\Models\Doctor;
use App\Models\Day;
use App\Models\DoctorDaysOff;
use App\Models\DoctorSchedule;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class BookAppointmentForm
{
    /**
     * Map Arabic day names to PHP Carbon day numbers (0=Sunday, 6=Saturday)
     */
    private static function getPhpDayNumberFromArabicName(string $arabicDay): ?int
    {
        $mapping = [
            'الأحد' => 0,      // Sunday
            'الاثنين' => 1,    // Monday
            'الثلاثاء' => 2,   // Tuesday
            'الأربعاء' => 3,   // Wednesday
            'الخميس' => 4,     // Thursday
            'الجمعة' => 5,     // Friday
            'السبت' => 6,      // Saturday
        ];

        return $mapping[$arabicDay] ?? null;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->options(fn (): array => Doctor::query()
                        ->join('users', 'users.id', '=', 'doctors.user_id')
                        ->orderBy('users.name')
                        ->pluck('users.name', 'doctors.id')
                        ->all())
                    ->required()
                    ->default(fn() => Auth::user()?->doctor?->id)
                    ->disabled(fn(string $operation) => !Auth::user()?->isAdmin() && $operation === 'edit')
                    ->hidden(fn() => Auth::user()?->isDoctor())
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('doctor_schedule_id', null);
                        $set('date', null);
                    })
                    ->columnSpan(2),

                Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(fn() => Auth::id())
                    ->disabled(fn() => !Auth::user()?->isAdmin())
                    ->hidden(fn() => !Auth::user()?->isAdmin())
                    ->columnSpan(2),

                Select::make('doctor_schedule_id')
                    ->label('جدول الطبيب (اليوم)')
                    ->options(function (Get $get): array {
                        $doctorId = $get('doctor_id');

                        if (! $doctorId) {
                            return [];
                        }

                        // Get days off for this doctor
                        $daysOff = DoctorDaysOff::where('doctor_id', $doctorId)
                            ->pluck('day_id')
                            ->toArray();

                        return DoctorSchedule::query()
                            ->with('day')
                            ->where('doctor_id', $doctorId)
                            ->whereNotIn('day_id', $daysOff) // Exclude days off
                            ->orderBy('day_id')
                            ->get()
                            ->mapWithKeys(fn($schedule) => [
                                $schedule->id => ($schedule->day?->day_name ?? Day::query()->where('id', $schedule->day_id)->value('day_name'))
                                    . ' (' . substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5) . ')',
                            ])
                            ->all();
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('date', null))
                    ->helperText('اختر اليوم المناسب من جدول الطبيب')
                    ->columnSpan(2),

                DatePicker::make('date')
                    ->label('تاريخ الموعد')
                    ->required()
                    ->minDate(now()->toDateString())
                    ->maxDate(now()->addMonths(6)->toDateString())
                    ->disabledDates(function (Get $get): array {
                        $scheduleId = $get('doctor_schedule_id');

                        if (!$scheduleId) {
                            // If no schedule selected, disable all dates
                            $disabledDates = [];
                            $date = now();
                            for ($i = 0; $i < 180; $i++) {
                                $disabledDates[] = $date->copy()->addDays($i)->format('Y-m-d');
                            }
                            return $disabledDates;
                        }

                        // Get the schedule's day
                        $schedule = DoctorSchedule::with('day')->find($scheduleId);
                        if (!$schedule || !$schedule->day) {
                            return [];
                        }

                        // Get the day name and convert to PHP day number
                        $dayName = $schedule->day->day_name;
                        $allowedPhpDayNumber = self::getPhpDayNumberFromArabicName($dayName);

                        if ($allowedPhpDayNumber === null) {
                            return [];
                        }

                        // Generate list of DISABLED dates (all dates that DON'T match the selected day)
                        $disabledDates = [];
                        $startDate = now();
                        
                        // Check next 180 days (6 months)
                        for ($i = 0; $i < 180; $i++) {
                            $currentDate = $startDate->copy()->addDays($i);
                            $currentDayOfWeek = (int) $currentDate->dayOfWeek; // 0=Sunday, 6=Saturday
                            
                            // If this date's day of week doesn't match the allowed day, disable it
                            if ($currentDayOfWeek !== $allowedPhpDayNumber) {
                                $disabledDates[] = $currentDate->format('Y-m-d');
                            }
                        }

                        return $disabledDates;
                    })
                    ->helperText(function (Get $get): string {
                        $scheduleId = $get('doctor_schedule_id');
                        if (!$scheduleId) {
                            return '⚠️ اختر جدول الطبيب أولاً لتحديد الأيام المتاحة';
                        }

                        $schedule = DoctorSchedule::with('day')->find($scheduleId);
                        if (!$schedule || !$schedule->day) {
                            return '';
                        }

                        return '✅ يمكنك فقط اختيار أيام ' . $schedule->day->day_name;
                    })
                    ->native(false) // Use Filament's date picker for better control
                    ->displayFormat('Y-m-d')
                    ->columnSpan(2),

                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                    ])
                    ->default('pending')
                    ->required()
                    ->disabled(fn() => !Auth::user()?->isAdmin() && !Auth::user()?->isDoctor())
                    ->visible(fn() => Auth::user()?->isAdmin() || Auth::user()?->isDoctor())
                    ->columnSpan(1),

                Toggle::make('is_completed')
                    ->label('مكتمل')
                    ->disabled(fn() => !Auth::user()?->isAdmin() && !Auth::user()?->isDoctor())
                    ->visible(fn() => Auth::user()?->isAdmin() || Auth::user()?->isDoctor())
                    ->columnSpan(1),

                Select::make('payment_mode')
                    ->label('طريقة الدفع')
                    ->options([
                        'cash' => 'نقداً',
                        'online' => 'أونلاين',
                    ])
                    ->required()
                    ->disabled(fn(string $operation) => !Auth::user()?->isAdmin() && $operation === 'edit')
                    ->columnSpan(1),

                Select::make('transaction_id')
                    ->label('رقم العملية')
                    ->relationship('transaction', 'id')
                    ->hidden(fn() => !Auth::user()?->isAdmin())
                    ->columnSpan(1),
            ]);
    }
}
