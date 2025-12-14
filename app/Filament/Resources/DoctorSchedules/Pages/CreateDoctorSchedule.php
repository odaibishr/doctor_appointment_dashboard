<?php

namespace App\Filament\Resources\DoctorSchedules\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\DoctorSchedules\DoctorScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateDoctorSchedule extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
