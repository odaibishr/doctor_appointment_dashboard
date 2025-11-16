<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    //

    protected $table = 'hospitals';

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'image',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // public function doctors()
    // {
    //     return $this->hasMany(Doctor::class);
    // }
}
