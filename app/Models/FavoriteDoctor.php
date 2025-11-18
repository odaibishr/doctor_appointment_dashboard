<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteDoctor extends Model
{
    //
    protected $table = "favorite_doctors";

    protected $fillable = [
        'doctor_id',
        'user_id',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
