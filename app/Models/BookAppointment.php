<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookAppointment extends Model
{
    protected $table = 'book_appointments';

    protected $fillable = [
        'doctor_id',
        'user_id',
        'doctor_schedule_id',
        'date',
        'status',
        'is_completed',
        'is_returning',
        'payment_mode',
        'transaction_id',
        'cancellation_reason',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id')->withDefault();
    }

    public function paymentGatewayDetail()
    {
        return $this->hasOne(PaymentGatewayDetail::class, 'transaction_id', 'id');
    }
}
