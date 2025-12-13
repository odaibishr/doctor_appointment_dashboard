<?php

namespace App\Filament\Resources\BookAppointments\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\BookAppointments\BookAppointmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookAppointment extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = BookAppointmentResource::class;
}

