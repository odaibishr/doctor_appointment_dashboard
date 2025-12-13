<?php

namespace App\Policies;

use App\Models\DoctorDaysOff;
use App\Models\User;

class DoctorDaysOffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor();
    }

    public function view(User $user, DoctorDaysOff $doctorDaysOff): bool
    {
        return $this->owns($user, $doctorDaysOff);
    }

    public function create(User $user): bool
    {
        return $user->isDoctor() && $user->doctor()->exists();
    }

    public function update(User $user, DoctorDaysOff $doctorDaysOff): bool
    {
        return $this->owns($user, $doctorDaysOff);
    }

    public function delete(User $user, DoctorDaysOff $doctorDaysOff): bool
    {
        return $this->owns($user, $doctorDaysOff);
    }

    private function owns(User $user, DoctorDaysOff $doctorDaysOff): bool
    {
        $doctorId = $user->doctor?->id;

        return $user->isDoctor() && $doctorId !== null && (int) $doctorDaysOff->doctor_id === (int) $doctorId;
    }
}

