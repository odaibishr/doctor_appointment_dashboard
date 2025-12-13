<?php

namespace App\Filament\Resources\DoctorSchedules\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\DoctorSchedules\DoctorScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctorSchedule extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorScheduleResource::class;
}

