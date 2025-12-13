<?php

namespace App\Filament\Resources\FavoriteDoctors\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\FavoriteDoctors\FavoriteDoctorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFavoriteDoctor extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = FavoriteDoctorResource::class;
}

