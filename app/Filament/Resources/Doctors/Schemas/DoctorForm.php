<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required(),

                TextInput::make(name: 'password')
                    ->label('Password')
                    ->password()
                    ->required(),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->required()
                    ->columnSpan(1),

                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->required()
                    ->columnSpan(1),

                DatePicker::make('birthday')
                    ->label('Birthday')
                    ->columnSpan(1),

                Select::make('specialty_id')
                    ->label('Specialty')
                    ->relationship('specialty', 'name')
                    ->required()
                    ->columnSpan(1),

                Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name')
                    ->required()
                    ->columnSpan(1),

                Select::make('hospital_id')
                    ->label('Hospital')
                    ->relationship('hospital', 'name')
                    ->columnSpan(1),

                Textarea::make('aboutus')
                    ->label('About Us')
                    ->columnSpanFull(),

                Textarea::make('services')
                    ->label('Services')
                    ->columnSpanFull(),

                Toggle::make('is_featured')
                    ->label('Featured')
                    ->columnSpan(1),

                Toggle::make('is_top_doctor')
                    ->label('Top Doctor')
                    ->columnSpan(1),

                FileUpload::make('profile_image')
                    ->label('Profile Image')
                    ->image()
                    // ->imagePreviewHeight(200)
                    // ->imageCropAspectRatio('1:1')
                    // ->imageResizeTargetHeight(200)
                    // ->imageResizeTargetWidth(200)
                    ->disk('public')
                    ->directory('doctors')
                    ->columnSpan(2),

            ]);
    }
}
