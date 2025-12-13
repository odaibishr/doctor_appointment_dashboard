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
                    ->label('الاسم الكامل')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),

                TextInput::make(name: 'password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(),

                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->columnSpan(1),

                Select::make('gender')
                    ->label('الجنس')
                    ->options([
                        'Male' => 'ذكر',
                        'Female' => 'أنثى',
                    ])
                    ->required()
                    ->columnSpan(1),

                DatePicker::make('birthday')
                    ->label('تاريخ الميلاد')
                    ->columnSpan(1),

                Select::make('specialty_id')
                    ->label('التخصص')
                    ->relationship('specialty', 'name')
                    ->required()
                    ->columnSpan(1),

                Select::make('location_id')
                    ->label('الموقع')
                    ->relationship('location', 'name')
                    ->required()
                    ->columnSpan(1),

                Select::make('hospital_id')
                    ->label('المستشفى')
                    ->relationship('hospital', 'name')
                    ->columnSpan(1),

                Textarea::make('aboutus')
                    ->label('نبذة عن الطبيب')
                    ->columnSpanFull(),

                Textarea::make('services')
                    ->label('الخدمات')
                    ->columnSpanFull(),

                Toggle::make('is_featured')
                    ->label('مميز')
                    ->columnSpan(1),

                Toggle::make('is_top_doctor')
                    ->label('طبيب مميز')
                    ->columnSpan(1),

                FileUpload::make('profile_image')
                    ->label('الصورة الشخصية')
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
