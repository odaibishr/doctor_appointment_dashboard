<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PatientForm
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

                TextInput::make('password')
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
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ])
                    ->required()
                    ->columnSpan(1),

                DatePicker::make('birth_day')
                    ->label('تاريخ الميلاد')
                    ->columnSpan(1),

                Select::make('location_id')
                    ->label('الموقع')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('اسم الموقع')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('lat')
                            ->label('خط العرض')
                            ->numeric()
                            ->required(),
                        TextInput::make('lng')
                            ->label('خط الطول')
                            ->numeric()
                            ->required(),
                    ])
                    ->createOptionAction(fn ($action) => $action->modalHeading('إضافة موقع')->modalSubmitActionLabel('حفظ'))
                    ->createOptionUsing(function (array $data): int {
                        $name = trim((string) ($data['name'] ?? ''));
                        $lat = (string) ($data['lat'] ?? '');
                        $lng = (string) ($data['lng'] ?? '');

                        $existing = Location::query()
                            ->when($name !== '', fn ($q) => $q->where('name', $name))
                            ->when($lat !== '', fn ($q) => $q->where('lat', $lat))
                            ->when($lng !== '', fn ($q) => $q->where('lng', $lng))
                            ->first();

                        if ($existing) {
                            $existing->fill([
                                'name' => $name,
                                'lat' => $lat,
                                'lng' => $lng,
                            ])->save();

                            return (int) $existing->getKey();
                        }

                        return (int) Location::query()->create([
                            'name' => $name,
                            'lat' => $lat,
                            'lng' => $lng,
                        ])->getKey();
                    })
                    ->required()
                    ->columnSpan(1),

                FileUpload::make('profile_image')
                    ->label('الصورة الشخصية')
                    ->image()
                    ->imagePreviewHeight(200)
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetHeight(200)
                    ->imageResizeTargetWidth(200)
                    ->columnSpanFull(),
            ]);
    }
}
