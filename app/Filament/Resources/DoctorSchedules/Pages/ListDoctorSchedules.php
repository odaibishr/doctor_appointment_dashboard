<?php

namespace App\Filament\Resources\DoctorSchedules\Pages;

use App\Filament\Resources\DoctorSchedules\DoctorScheduleResource;
use App\Services\DoctorScheduleSyncService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoctorSchedules extends ListRecords
{
    protected static string $resource = DoctorScheduleResource::class;

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();

        if ($user?->isDoctor() && $user->doctor) {
            app(DoctorScheduleSyncService::class)->syncDefaultSchedulesForDoctor($user->doctor);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
