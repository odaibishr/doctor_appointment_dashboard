<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;

class SpecialtyPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Specialty $specialty): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Specialty $specialty): bool
    {
        return false;
    }

    public function delete(User $user, Specialty $specialty): bool
    {
        return false;
    }
}
