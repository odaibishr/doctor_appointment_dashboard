<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.user.name')
                    ->label('الطبيب')
                    ->searchable()
                    ->sortable()
                    ->columnSpan(2),

                TextColumn::make('user.name')
                    ->label('المريض')
                    ->searchable()
                    ->sortable()
                    ->columnSpan(2),

                TextColumn::make('rating')
                    ->label('التقييم')
                    ->numeric()
                    ->sortable()
                    ->columnSpan(1),

                TextColumn::make('comment')
                    ->label('التعليق')
                    ->limit(50)
                    ->searchable()
                    ->columnSpanFull(),

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
                        ->visible(fn (): bool => auth()->user()?->can('deleteAny', \App\Models\Review::class) ?? false),
                ]),
            ]);
    }
}
