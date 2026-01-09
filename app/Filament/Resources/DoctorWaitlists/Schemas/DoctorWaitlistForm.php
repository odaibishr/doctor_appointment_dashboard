<?php

namespace App\Filament\Resources\DoctorWaitlists\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class DoctorWaitlistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DatePicker::make('preferred_date')
                    ->label('التاريخ المفضل')
                    ->native(false),

                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'waiting' => 'في الانتظار',
                        'notified' => 'تم الإبلاغ',
                        'booked' => 'تم الحجز',
                        'expired' => 'منتهي الصلاحية',
                        'cancelled' => 'ملغي',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('position')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),

                Forms\Components\DateTimePicker::make('notified_at')
                    ->label('وقت الإبلاغ')
                    ->native(false),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('وقت الانتهاء')
                    ->native(false),
            ]);
    }
}
