<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'payment_gateway_detail_id',
        'amount',
        'status',
    ];

    public function paymentGatewayDetail()
    {
        return $this->belongsTo(PaymentGatewayDetail::class);
    }
}
