<?php

namespace App\Filament\Resources\Days\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Days\DayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDay extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DayResource::class;
}

