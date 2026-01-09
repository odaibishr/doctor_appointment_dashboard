<?php

namespace App\Filament\Resources\DoctorWaitlists\Pages;

use App\Filament\Resources\DoctorWaitlists\DoctorWaitlistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctorWaitlist extends CreateRecord
{
    protected static string $resource = DoctorWaitlistResource::class;
}
