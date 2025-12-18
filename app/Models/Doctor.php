<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    //

    protected $with = [
        'user',
        'user.location',
    ];

    protected $fillable = [
        'user_id',
        'aboutus',
        'days_off',
        'specialty_id',
        'department_id',
        'is_featured',
        'is_top_doctor',
        'services',
        'hospital_id',
        'birthday',
        'price',
        'experience',
    ];

    protected $appends = [
        'profile_image_url',
        'name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'location_id',
        'birth_day',
        'price',
        'experience',
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

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($path);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->name;
    }

    public function getEmailAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->email;
    }

    public function getPhoneAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->phone;
    }

    public function getGenderAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->gender;
    }

    public function getProfileImageAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->profile_image;
    }

    public function getLocationIdAttribute($value): ?int
    {
        if ($value !== null && $value !== '') {
            return (int) $value;
        }

        return $this->user?->location_id ? (int) $this->user->location_id : null;
    }

    public function getLocationAttribute(): ?Location
    {
        return $this->user?->location;
    }

    public function getBirthDayAttribute($value): mixed
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->user?->birth_date;
    }

    public function getPriceAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->price;
    }

    public function getExperienceAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->experience;
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

    protected static function booted(): void
    {
        static::saved(function (self $doctor): void {
            app(\App\Services\DoctorScheduleSyncService::class)->syncDefaultSchedulesForDoctor($doctor);
        });
    }
}
