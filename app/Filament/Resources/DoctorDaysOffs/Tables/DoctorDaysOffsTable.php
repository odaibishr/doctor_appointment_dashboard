<?php

namespace App\Filament\Resources\DoctorDaysOffs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DoctorDaysOffsTable
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
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => auth()->user()?->can('update', $record) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->can('deleteAny', \App\Models\DoctorDaysOff::class) ?? false),
                ]),
            ]);
    }
}
