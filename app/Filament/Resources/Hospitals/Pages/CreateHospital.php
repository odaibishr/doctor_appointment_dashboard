<?php

namespace App\Filament\Resources\Hospitals\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Hospitals\HospitalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHospital extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = HospitalResource::class;
}

