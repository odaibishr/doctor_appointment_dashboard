<?php

namespace App\Policies;

use App\Models\DoctorSchedule;
use App\Models\User;

class DoctorSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor();
    }

    public function view(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return $this->ownsSchedule($user, $doctorSchedule);
    }

    public function create(User $user): bool
    {
        return $user->isDoctor() && $user->doctor()->exists();
    }

    public function update(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return $this->ownsSchedule($user, $doctorSchedule);
    }

    public function delete(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return $this->ownsSchedule($user, $doctorSchedule);
    }

    private function ownsSchedule(User $user, DoctorSchedule $doctorSchedule): bool
    {
        $doctorId = $user->doctor?->id;

        return $user->isDoctor() && $doctorId !== null && (int) $doctorSchedule->doctor_id === (int) $doctorId;
    }
}

