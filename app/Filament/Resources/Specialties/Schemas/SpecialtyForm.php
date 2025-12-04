<?php

namespace App\Filament\Resources\Specialties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpecialtyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->columnSpan(2),

                FileUpload::make('icon')
                    ->label('Icon')
                    ->image()
                    ->disk('public')
                    ->directory('specialties')
                    ->columnSpan(2),

                Toggle::make('is_active')
                    ->label('Active')
                    ->columnSpan(1),
            ]);
    }
}
