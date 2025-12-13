<?php

namespace App\Policies;

use App\Models\FavoriteDoctor;
use App\Models\User;

class FavoriteDoctorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPatient();
    }

    public function view(User $user, FavoriteDoctor $favoriteDoctor): bool
    {
        return (int) $favoriteDoctor->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isPatient();
    }

    public function update(User $user, FavoriteDoctor $favoriteDoctor): bool
    {
        return (int) $favoriteDoctor->user_id === (int) $user->id;
    }

    public function delete(User $user, FavoriteDoctor $favoriteDoctor): bool
    {
        return (int) $favoriteDoctor->user_id === (int) $user->id;
    }
}

