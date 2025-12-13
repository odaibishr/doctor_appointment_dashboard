<?php

namespace App\Filament\Resources\Doctors\Schemas;

use App\Models\Location;
use App\Models\Hospital;
use App\Models\Specialty;
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
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('اسم التخصص')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('icon')
                            ->label('الأيقونة')
                            ->image()
                            ->disk('public')
                            ->directory('specialties')
                            ->columnSpan(2),
                        Toggle::make('is_active')
                            ->label('مفعل')
                            ->default(true),
                    ])
                    ->createOptionAction(fn ($action) => $action->modalHeading('إضافة تخصص')->modalSubmitActionLabel('حفظ'))
                    ->createOptionUsing(function (array $data): int {
                        $name = trim((string) ($data['name'] ?? ''));

                        $existing = Specialty::query()
                            ->when($name !== '', fn ($q) => $q->where('name', $name))
                            ->first();

                        if ($existing) {
                            $existing->fill($data)->save();

                            return (int) $existing->getKey();
                        }

                        return (int) Specialty::query()->create($data)->getKey();
                    })
                    ->required()
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

                Select::make('hospital_id')
                    ->label('المستشفى')
                    ->relationship('hospital', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('اسم المستشفى')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->label('الموقع الإلكتروني (اختياري)')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('العنوان')
                            ->required()
                            ->maxLength(255),
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
                            ->required(),
                        FileUpload::make('image')
                            ->label('شعار أو صورة المستشفى')
                            ->image()
                            ->disk('public')
                            ->directory('hospitals'),
                    ])
                    ->createOptionAction(fn ($action) => $action->modalHeading('إضافة مستشفى')->modalSubmitActionLabel('حفظ'))
                    ->createOptionUsing(function (array $data): int {
                        $name = trim((string) ($data['name'] ?? ''));
                        $phone = trim((string) ($data['phone'] ?? ''));
                        $email = trim((string) ($data['email'] ?? ''));

                        $existing = Hospital::query()
                            ->when($email !== '', fn ($q) => $q->where('email', $email))
                            ->when($email === '' && $name !== '' && $phone !== '', fn ($q) => $q->where('name', $name)->where('phone', $phone))
                            ->first();

                        if ($existing) {
                            $existing->fill($data)->save();

                            return (int) $existing->getKey();
                        }

                        return (int) Hospital::query()->create($data)->getKey();
                    })
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
                    ->label('افضل الاطباء')
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
