<?php

namespace App\Policies;

use App\Models\Day;
use App\Models\User;

class DayPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Day $day): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Day $day): bool
    {
        return false;
    }

    public function delete(User $user, Day $day): bool
    {
        return false;
    }
}
