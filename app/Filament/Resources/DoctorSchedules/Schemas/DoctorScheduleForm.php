<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

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
                    ->columnSpan(2),

                Select::make('day_id')
                    ->label('اليوم')
                    ->relationship('day', 'day_name') 
                    ->required()
                    ->columnSpan(2),

                TimePicker::make('start_time')
                    ->label('بداية العمل')
                    ->required()
                    ->columnSpan(1),

                TimePicker::make('end_time')
                    ->label('نهاية العمل')
                    ->required()
                    ->columnSpan(1),
            ]);
    }
}
