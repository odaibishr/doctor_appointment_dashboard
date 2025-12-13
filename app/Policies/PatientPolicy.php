<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPatient();
    }

    public function view(User $user, Patient $patient): bool
    {
        return (int) $patient->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isPatient();
    }

    public function update(User $user, Patient $patient): bool
    {
        return (int) $patient->user_id === (int) $user->id;
    }

    public function delete(User $user, Patient $patient): bool
    {
        return false;
    }
}

