<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
 protected $fillable = [
        'name',
        'logo',
        'is_active'
    ];
}
