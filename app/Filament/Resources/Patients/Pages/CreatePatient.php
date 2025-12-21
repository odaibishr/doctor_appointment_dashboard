<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Patients\PatientResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = PatientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = User::ROLE_PATIENT;

        return $data;
    }
}
