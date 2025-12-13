<?php

namespace App\Filament\Resources\DoctorDaysOffs\Pages;

use App\Filament\Resources\DoctorDaysOffs\DoctorDaysOffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoctorDaysOffs extends ListRecords
{
    protected static string $resource = DoctorDaysOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
