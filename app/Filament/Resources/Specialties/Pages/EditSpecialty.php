<?php

namespace App\Filament\Resources\Specialties\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Specialties\SpecialtyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecialty extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = SpecialtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

