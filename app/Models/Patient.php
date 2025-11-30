<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //

    protected $fillable = [
        'phone',
        'birth_date',
        'profile_image',
        'gender',
        'user_id',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function appointments()
    {
        return $this->hasMany(BookAppointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoriteDoctors()
    {
        return $this->hasMany(FavoriteDoctor::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
