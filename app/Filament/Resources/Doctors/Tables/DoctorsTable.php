<?php

namespace App\Filament\Resources\Doctors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الطبيب')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('aboutus')
                    ->label('نبذة عن الطبيب')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('location.name')
                    ->label('الموقع')
                    ->sortable(),

                TextColumn::make('specialty.name')
                    ->label('التخصص')
                    ->sortable(),

                TextColumn::make('hospital.name')
                    ->label('المستشفى')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('gender')
                    ->label('الجنس')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),

                IconColumn::make('is_top_doctor')
                    ->label('افضل الاطباء')
                    ->boolean()
                    ->toggleable(),

                ImageColumn::make('profile_image')
                    ->label('الصورة')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('birth_day')
                    ->label('تاريخ الميلاد')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ])
                    ->label('الجنس'),

                SelectFilter::make('specialty_id')
                    ->relationship('specialty', 'name')
                    ->label('التخصص'),

                SelectFilter::make('hospital_id')
                    ->relationship('hospital', 'name')
                    ->label('المستشفى'),

                TernaryFilter::make('is_featured')
                    ->label('مميز'),

                TernaryFilter::make('is_top_doctor')
                    ->label('طبيب مميز'),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => auth()->user()?->can('update', $record) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->can('deleteAny', \App\Models\Doctor::class) ?? false),
                ]),
            ]);
    }
}
