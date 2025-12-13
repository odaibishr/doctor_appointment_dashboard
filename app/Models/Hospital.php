<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hospital extends Model
{
    //

    protected $table = 'hospitals';

    // protected $fillable = [
    //     'name',
    //     'email',
    //     'address',
    //     'phone',
    //     'image',
    //     'location_id',
    // ];
    protected $guarded=[];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        $path = (string) ($this->image ?? '');
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
