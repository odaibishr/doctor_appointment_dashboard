<?php

namespace App\Services;

use App\Models\Day;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSyncService
{
    public function syncDefaultSchedulesForDoctor(Doctor $doctor): void
    {
        $allDayIds = Day::query()
            ->orderBy('day_number')
            ->pluck('id')
            ->all();

        if ($allDayIds === []) {
            return;
        }

        $daysOffIds = $doctor->daysOff()
            ->pluck('day_id')
            ->all();

        if ($daysOffIds !== []) {
            DoctorSchedule::query()
                ->where('doctor_id', $doctor->id)
                ->whereIn('day_id', $daysOffIds)
                ->delete();
        }

        $workingDayIds = array_values(array_diff($allDayIds, $daysOffIds));

        foreach ($workingDayIds as $dayId) {
            DoctorSchedule::query()->firstOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'day_id' => $dayId,
                ],
                [
                    'start_time' => '00:00:00',
                    'end_time' => '00:00:00',
                ],
            );
        }
    }
}

