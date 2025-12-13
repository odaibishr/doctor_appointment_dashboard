<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayDetail extends Model
{
    //
    protected $table = 'payment_gateway_details';

    protected $fillable = [
        'gateway_name',
        'api_key',
        'api_secret',
        'is_active',
        'logo',
        'status',
        'logo_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
