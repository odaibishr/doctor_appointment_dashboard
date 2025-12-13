<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HospitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Hospital Name')
                    ->required(),

                TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                TextInput::make('website')
                    ->label('Website (optional)')
                    ->url(),

                TextInput::make('address')
                    ->label('Address')
                    ->required(),

                FileUpload::make('image')
                    ->label('Hospital Logo / Image')
                    ->image()
                    ->disk('public')
                    ->directory('hospitals'),

                Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->required(),

                Select::make('doctors')
                    ->label('Doctors in Hospital')
                    ->multiple()
                    ->relationship('doctors', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
