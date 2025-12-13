<?php

namespace App\Filament\Resources\Hospitals\Schemas;

use App\Models\Location;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

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
                    ->label('الموقع الإلكتروني')
                    ->url(),

                TextInput::make('address')
                    ->label('العنوان')
                    ->required(),

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
                    ->createOptionAction(fn ($action) => $action
                        ->visible(fn () => Auth::user()?->isAdmin())
                        ->modalHeading('إضافة موقع')
                        ->modalSubmitActionLabel('حفظ'))
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
                    ->label('صورة المستشفى')
                    ->image()
                    ->disk('public')
                    ->directory('hospitals')
                    ->columnSpan(span: 2),
            ]);
    }
}
