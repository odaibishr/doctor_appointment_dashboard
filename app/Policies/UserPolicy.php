<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPatient();
    }

    public function view(User $user, User $record): bool
    {
        return $record->isPatient() && (int) $record->id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $record): bool
    {
        return $record->isPatient() && (int) $record->id === (int) $user->id;
    }

    public function delete(User $user, User $record): bool
    {
        return false;
    }
}

