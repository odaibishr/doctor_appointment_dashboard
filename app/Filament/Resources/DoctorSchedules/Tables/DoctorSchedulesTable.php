<?php

namespace App\Filament\Resources\DoctorSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DoctorSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.user.name')
                    ->label('الطبيب')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('day.day_name')
                    ->label('اليوم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('بداية العمل')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('نهاية العمل')
                    ->time()
                    ->sortable(),
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
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => auth()->user()?->can('update', $record) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->can('deleteAny', \App\Models\DoctorSchedule::class) ?? false),
                ]),
            ]);
    }
}
