<?php

namespace App\Filament\Resources\FavoriteDoctors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FavoriteDoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->required(),
                Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }
}
