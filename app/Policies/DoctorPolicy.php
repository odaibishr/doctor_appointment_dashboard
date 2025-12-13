<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return $user->isDoctor() && (int) $doctor->user_id === (int) $user->id;
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return false;
    }
}

