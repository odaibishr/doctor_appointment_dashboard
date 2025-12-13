<?php

namespace App\Filament\Resources\DoctorDaysOffs\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\DoctorDaysOffs\DoctorDaysOffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctorDaysOff extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorDaysOffResource::class;
}

