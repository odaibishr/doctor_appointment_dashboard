<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'name')
                    ->required()
                    ->default(fn () => Auth::user()?->doctor?->id)
                    ->disabled(fn () => ! Auth::user()?->isAdmin())
                    ->hidden(fn () => ! Auth::user()?->isAdmin())
                    ->columnSpan(2),

                Select::make('day_id')
                    ->label('اليوم')
                    ->relationship('day', 'day_name')
                    ->required()
                    ->columnSpan(2),

                TimePicker::make('start_time')
                    ->label('وقت البداية')
                    ->required()
                    ->columnSpan(1),

                TimePicker::make('end_time')
                    ->label('وقت النهاية')
                    ->required()
                    ->columnSpan(1),
            ]);
    }
}

