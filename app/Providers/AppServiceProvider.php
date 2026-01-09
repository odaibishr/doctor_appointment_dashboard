<?php

namespace App\Providers;

use App\Models\BookAppointment;
use App\Observers\BookAppointmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BookAppointment::observe(BookAppointmentObserver::class);
    }
}
