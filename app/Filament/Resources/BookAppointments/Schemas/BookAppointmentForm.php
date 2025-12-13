<?php

namespace App\Filament\Resources\BookAppointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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
                    ->columnSpan(2),

                Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->required()
                    ->columnSpan(2),

                Select::make('doctor_schedule_id')
                    ->label('جدول الطبيب')
                    ->relationship('schedule', 'id')
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
                        'cancelled' => 'ملغى',
                    ])
                    ->default('pending')
                    ->required()
                    ->columnSpan(1),

                Toggle::make('is_completed')
                    ->label('مكتمل')
                    ->columnSpan(1),

                TextInput::make('payment_mode')
                    ->label('طريقة الدفع')
                    ->columnSpan(1),

                Select::make('transaction_id')
                    ->label('المعاملة')
                    ->relationship('transaction', 'id')
                    ->columnSpan(1),
            ]);
    }
}
