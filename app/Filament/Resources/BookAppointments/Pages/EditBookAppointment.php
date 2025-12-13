<?php

namespace App\Filament\Resources\BookAppointments\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\BookAppointments\BookAppointmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookAppointment extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = BookAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

