<?php

namespace App\Filament\Resources\BookAppointments\Tables;

use App\Models\Doctor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookAppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.user.name')
                    ->label('الطبيب')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('المريض')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('schedule.day.name')
                    ->label('اليوم')
                    ->sortable(),

                TextColumn::make('date')
                    ->label('تاريخ الموعد')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_completed')
                    ->label('مكتمل')
                    ->boolean(),

                TextColumn::make('payment_mode')
                    ->label('طريقة الدفع')
                    ->searchable(),

                TextColumn::make('transaction_id')
                    ->label('رقم المعاملة')
                    ->numeric()
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
                SelectFilter::make('doctor_id')
                    ->options(fn (): array => Doctor::query()
                        ->join('users', 'users.id', '=', 'doctors.user_id')
                        ->orderBy('users.name')
                        ->pluck('users.name', 'doctors.id')
                        ->all())
                    ->label('الطبيب'),

                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('المريض'),

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغى',
                    ])
                    ->label('الحالة'),

                Filter::make('date')
                    ->label('تاريخ الموعد')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('من'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('إلى'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'] ?? null, fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => auth()->user()?->can('update', $record) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->can('deleteAny', \App\Models\BookAppointment::class) ?? false),
                ]),
            ]);
    }
}
