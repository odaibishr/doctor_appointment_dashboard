<?php

namespace App\Filament\Resources\DoctorWaitlists\Tables;

use App\Models\DoctorWaitlist;
use Filament\Tables;
use Filament\Tables\Table;

class DoctorWaitlistTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('المريض')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('الطبيب')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('التاريخ المفضل')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('الترتيب')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting' => 'warning',
                        'notified' => 'info',
                        'booked' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'waiting' => 'في الانتظار',
                        'notified' => 'تم الإبلاغ',
                        'booked' => 'تم الحجز',
                        'expired' => 'منتهي الصلاحية',
                        'cancelled' => 'ملغي',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('notified_at')
                    ->label('وقت الإبلاغ')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('ينتهي في')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'waiting' => 'في الانتظار',
                        'notified' => 'تم الإبلاغ',
                        'booked' => 'تم الحجز',
                        'expired' => 'منتهي الصلاحية',
                        'cancelled' => 'ملغي',
                    ]),

                Tables\Filters\SelectFilter::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('notify')
                    ->label('إبلاغ')
                    ->icon('heroicon-o-bell')
                    ->color('info')
                    ->visible(fn(DoctorWaitlist $record) => $record->status === 'waiting')
                    ->requiresConfirmation()
                    ->action(fn(DoctorWaitlist $record) => $record->markAsNotified(15)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
