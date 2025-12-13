<?php

namespace App\Filament\Resources\Days\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('day_name')
                    ->label('اسم اليوم')
                    ->required(),

                TextInput::make('short_name')
                    ->label('الاختصار')
                    ->required(),

                TextInput::make('day_number')
                    ->label('رقم اليوم')
                    ->required()
                    ->numeric(),
            ]);
    }
}

