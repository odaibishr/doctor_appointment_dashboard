<?php

namespace App\Filament\Resources\DoctorDaysOffs\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\DoctorDaysOffs\DoctorDaysOffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditDoctorDaysOff extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorDaysOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => static::getResource()::canDelete($this->getRecord())),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        if (! $user) {
            return $data;
        }

        if ($user->isAdmin()) {
            return $data;
        }

        if (! $user->isDoctor()) {
            return $data;
        }

        $doctorId = $user->doctor?->id;

        if (! $doctorId) {
            throw ValidationException::withMessages([
                'doctor_id' => 'لا يوجد حساب طبيب مرتبط بهذا المستخدم.',
            ]);
        }

        $data['doctor_id'] = $doctorId;

        return $data;
    }
}
