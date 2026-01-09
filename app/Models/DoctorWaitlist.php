<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorWaitlist extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'preferred_date',
        'preferred_schedule_id',
        'status',
        'notified_at',
        'expires_at',
        'position',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function preferredSchedule(): BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class, 'preferred_schedule_id');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    public function scopeForDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeOrderedByPosition($query)
    {
        return $query->orderBy('position')->orderBy('created_at');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markAsNotified(int $expiresInMinutes = 15): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now(),
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }

    public function markAsBooked(): void
    {
        $this->update(['status' => 'booked']);
    }

    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
