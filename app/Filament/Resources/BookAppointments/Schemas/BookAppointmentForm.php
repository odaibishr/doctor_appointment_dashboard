<?php

namespace App\Filament\Resources\BookAppointments\Schemas;

use App\Models\Doctor;
use App\Models\Day;
use App\Models\DoctorSchedule;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class BookAppointmentForm
{
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
                    ->afterStateUpdated(fn(Set $set) => $set('doctor_schedule_id', null))
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
                    ->label('جدول الطبيب')
                    ->options(function (Get $get): array {
                        $doctorId = $get('doctor_id');

                        if (! $doctorId) {
                            return [];
                        }

                        return DoctorSchedule::query()
                            ->with('day')
                            ->where('doctor_id', $doctorId)
                            ->orderBy('day_id')
                            ->get()
                            ->mapWithKeys(fn($schedule) => [
                                $schedule->id => $schedule->day?->day_name
                                    ?? Day::query()->where('id', $schedule->day_id)->value('day_name'),
                            ])
                            ->all();
                    })
                    ->required()
                    ->live()
                    ->columnSpan(2),

                DatePicker::make('date')
                    ->label('تاريخ الموعد')
                    ->required()
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
