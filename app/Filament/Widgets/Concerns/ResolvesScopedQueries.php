<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\BookAppointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ResolvesScopedQueries
{
    protected function getAuthUser(): ?User
    {
        return Auth::user();
    }

    protected function getScopedAppointmentsQuery(): Builder
    {
        $query = BookAppointment::query();
        $user = $this->getAuthUser();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isDoctor()) {
            $doctorId = $user->doctor?->id;

            return $doctorId ? $query->where('doctor_id', $doctorId) : $query->whereRaw('1 = 0');
        }

        return $query->where('user_id', $user->id);
    }
}

