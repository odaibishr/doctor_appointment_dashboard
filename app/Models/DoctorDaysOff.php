<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDaysOff extends Model
{
    //
    protected $table = 'doctor_days_offs';

    protected $fillable = [
        'doctor_id',
        'day_id',
    ];
    

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    protected static function booted(): void
    {
        $sync = function (self $dayOff): void {
            $doctor = $dayOff->doctor()->first();

            if (! $doctor) {
                return;
            }

            app(\App\Services\DoctorScheduleSyncService::class)->syncDefaultSchedulesForDoctor($doctor);
        };

        static::saved($sync);
        static::deleted($sync);
    }
}
