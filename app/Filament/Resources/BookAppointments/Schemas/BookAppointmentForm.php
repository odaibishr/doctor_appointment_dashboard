<?php

namespace App\Filament\Resources\BookAppointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Day;

class BookAppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'name')
                    ->required()
                    ->disabled(fn(string $operation) => !Auth::user()?->isAdmin() && $operation === 'edit')
                    ->hidden(fn() => Auth::user()?->isDoctor())
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
                    ->relationship('schedule', 'day_id', )
                    ->getOptionLabelUsing(fn($value) => Day::query()->where('id', $value)->value('day_name'))
                    ->required()
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

