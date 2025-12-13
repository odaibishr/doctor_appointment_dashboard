<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    //

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'aboutus',
        'days_off',
        'address',
        'phone',
        'location_id',
        'specialty_id',
        'department_id',
        'gender',
        'is_featured',
        'is_top_doctor',
        'profile_image',
        'birthday',
        'services',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_image_url',
    ];

    public function getProfileImageUrlAttribute(): ?string
    {
        $path = (string) ($this->profile_image ?? '');
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(BookAppointment::class);
    }

    public function daysOff()
    {
        return $this->hasMany(DoctorDaysOff::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function favoriteDoctors()
    {
        return $this->hasMany(FavoriteDoctor::class);
    }
}
