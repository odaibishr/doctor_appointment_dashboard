<?php

namespace App\Filament\Resources\FavoriteDoctors\Pages;

use App\Filament\Resources\FavoriteDoctors\FavoriteDoctorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFavoriteDoctors extends ListRecords
{
    protected static string $resource = FavoriteDoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
