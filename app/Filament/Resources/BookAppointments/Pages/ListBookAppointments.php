<?php

namespace App\Filament\Resources\BookAppointments\Pages;

use App\Filament\Resources\BookAppointments\BookAppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookAppointments extends ListRecords
{
    protected static string $resource = BookAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
