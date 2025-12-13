<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lat')
                    ->label('خط العرض')
                    ->required()
                    ->numeric(),

                TextInput::make('lng')
                    ->label('خط الطول')
                    ->required()
                    ->numeric(),

                TextInput::make('name')
                    ->label('اسم الموقع'),
            ]);
    }
}

