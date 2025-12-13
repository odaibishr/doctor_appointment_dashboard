<?php

namespace App\Filament\Resources\Locations\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Locations\LocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocation extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = LocationResource::class;
}

