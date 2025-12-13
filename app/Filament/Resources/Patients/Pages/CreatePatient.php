<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Patients\PatientResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = PatientResource::class;
}

