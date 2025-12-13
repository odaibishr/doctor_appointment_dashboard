<?php

namespace App\Filament\Resources\Hospitals\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Hospitals\HospitalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHospital extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = HospitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

