<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor() || $user->isPatient();
    }

    public function view(User $user, Review $review): bool
    {
        return $this->isVisibleToUser($user, $review);
    }

    public function create(User $user): bool
    {
        return $user->isPatient();
    }

    public function update(User $user, Review $review): bool
    {
        return $user->isPatient() && (int) $review->user_id === (int) $user->id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->isPatient() && (int) $review->user_id === (int) $user->id;
    }

    private function isVisibleToUser(User $user, Review $review): bool
    {
        if ($user->isPatient()) {
            return (int) $review->user_id === (int) $user->id;
        }

        $doctorId = $user->doctor?->id;

        return $user->isDoctor() && $doctorId !== null && (int) $review->doctor_id === (int) $doctorId;
    }
}

