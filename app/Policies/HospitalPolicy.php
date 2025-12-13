<?php

namespace App\Policies;

use App\Models\Hospital;
use App\Models\User;

class HospitalPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Hospital $hospital): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Hospital $hospital): bool
    {
        return false;
    }

    public function delete(User $user, Hospital $hospital): bool
    {
        return false;
    }
}
