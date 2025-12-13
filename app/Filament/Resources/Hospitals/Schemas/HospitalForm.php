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
                    ->label('اسم المستشفى')
                    ->required(),

                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required(),

                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),

                TextInput::make('website')
                    ->label('الموقع الإلكتروني (اختياري)')
                    ->url(),

                TextInput::make('address')
                    ->label('العنوان')
                    ->required(),

                FileUpload::make('image')
                    ->label('شعار أو صورة المستشفى')
                    ->image()
                    ->disk('public')
                    ->directory('hospitals'),

                Select::make('location_id')
                    ->label('الموقع')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->required(),

                Select::make('doctors')
                    ->label('الأطباء في المستشفى')
                    ->multiple()
                    ->relationship('doctors', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
