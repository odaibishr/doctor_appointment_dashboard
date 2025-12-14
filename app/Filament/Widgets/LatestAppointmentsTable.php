<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookAppointments\BookAppointmentResource;
use App\Filament\Widgets\Concerns\ResolvesScopedQueries;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestAppointmentsTable extends TableWidget
{
    use ResolvesScopedQueries;

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('آخر الحجوزات')
            ->description('أحدث الحجوزات حسب الصلاحيات')
            ->query(
                $this->getScopedAppointmentsQuery()
                    ->latest('date')
                    ->latest('id'),
            )
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25])
            ->columns([
                TextColumn::make('doctor.name')
                    ->label('الطبيب')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('المريض')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('payment_mode')
                    ->label('الدفع')
                    ->formatStateUsing(fn (string $state): string => $state === 'online' ? 'أونلاين' : 'نقدي')
                    ->badge(),
            ])
            ->recordUrl(function ($record): ?string {
                if (! (auth()->user()?->can('update', $record) ?? false)) {
                    return null;
                }

                return BookAppointmentResource::getUrl('edit', ['record' => $record]);
            });
    }
}

