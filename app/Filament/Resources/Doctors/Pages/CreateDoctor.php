<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Doctors\DoctorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctor extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorResource::class;
}

