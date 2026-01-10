<?php

namespace App\Filament\Resources\DoctorWaitlists\Pages;

use App\Filament\Resources\DoctorWaitlists\DoctorWaitlistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoctorWaitlist extends EditRecord
{
    protected static string $resource = DoctorWaitlistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
