<?php

namespace App\Providers;

use App\Models\BookAppointment;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\DoctorDaysOff;
use App\Models\DoctorSchedule;
use App\Models\FavoriteDoctor;
use App\Models\Hospital;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\PaymentGatewayDetail;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Specialty;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\BookAppointmentPolicy;
use App\Policies\DayPolicy;
use App\Policies\DoctorDaysOffPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\DoctorSchedulePolicy;
use App\Policies\FavoriteDoctorPolicy;
use App\Policies\HospitalPolicy;
use App\Policies\LocationPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PaymentGatewayDetailPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SpecialtyPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Doctor::class => DoctorPolicy::class,
        Patient::class => PatientPolicy::class,
        DoctorSchedule::class => DoctorSchedulePolicy::class,
        DoctorDaysOff::class => DoctorDaysOffPolicy::class,
        BookAppointment::class => BookAppointmentPolicy::class,
        Review::class => ReviewPolicy::class,
        FavoriteDoctor::class => FavoriteDoctorPolicy::class,
        Notification::class => NotificationPolicy::class,
        Transaction::class => TransactionPolicy::class,
        PaymentGatewayDetail::class => PaymentGatewayDetailPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        Specialty::class => SpecialtyPolicy::class,
        Location::class => LocationPolicy::class,
        Hospital::class => HospitalPolicy::class,
        Day::class => DayPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability): bool|null {
            return $user->isAdmin() ? true : null;
        });
    }
}

