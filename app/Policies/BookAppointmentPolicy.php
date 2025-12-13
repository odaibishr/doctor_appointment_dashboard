<?php

namespace App\Policies;

use App\Models\BookAppointment;
use App\Models\User;

class BookAppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, BookAppointment $bookAppointment): bool
    {
        return $this->isAppointmentVisibleToUser($user, $bookAppointment);
    }

    public function create(User $user): bool
    {
        return $user->isPatient();
    }

    public function update(User $user, BookAppointment $bookAppointment): bool
    {
        if ($user->isDoctor()) {
            return $this->isAppointmentVisibleToUser($user, $bookAppointment);
        }

        return $user->isPatient()
            && (int) $bookAppointment->user_id === (int) $user->id
            && (string) $bookAppointment->status === 'pending';
    }

    public function delete(User $user, BookAppointment $bookAppointment): bool
    {
        return $user->isPatient()
            && (int) $bookAppointment->user_id === (int) $user->id
            && (string) $bookAppointment->status === 'pending';
    }

    private function isAppointmentVisibleToUser(User $user, BookAppointment $bookAppointment): bool
    {
        if ($user->isPatient()) {
            return (int) $bookAppointment->user_id === (int) $user->id;
        }

        $doctorId = $user->doctor?->id;

        return $user->isDoctor() && $doctorId !== null && (int) $bookAppointment->doctor_id === (int) $doctorId;
    }
}

