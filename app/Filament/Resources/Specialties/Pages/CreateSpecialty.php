<?php

namespace App\Filament\Resources\Specialties\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Specialties\SpecialtyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialty extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = SpecialtyResource::class;
}

