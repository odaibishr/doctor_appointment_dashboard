<?php

namespace App\Observers;

use App\Models\BookAppointment;
use App\Models\DoctorWaitlist;
use App\Models\Notification;

class BookAppointmentObserver
{

    public function creating(BookAppointment $bookAppointment): void {
        $hasVisited = BookAppointment::where('user_id', $bookAppointment->user_id)
            ->where('doctor_id', $bookAppointment->doctor_id)
            ->where('status', '!=', 'cancelled')
            ->exists();

            $bookAppointment->is_returning = $hasVisited;
    }
    public function updated(BookAppointment $appointment): void
    {
        if ($appointment->isDirty('status') && $appointment->status === 'cancelled') {
            $this->handleCancellation($appointment);
        }
    }

    public function deleted(BookAppointment $appointment): void
    {
        $this->handleCancellation($appointment);
    }

    private function handleCancellation(BookAppointment $appointment): void
    {
        $nextInWaitlist = DoctorWaitlist::where('doctor_id', $appointment->doctor_id)
            ->where('status', 'waiting')
            ->orderedByPosition()
            ->first();

        if (!$nextInWaitlist) {
            return;
        }

        $nextInWaitlist->markAsNotified(15);

        $doctorName = $appointment->doctor?->name ?? 'الطبيب';

        Notification::create([
            'user_id' => $nextInWaitlist->user_id,
            'title' => '🎉 موعد متاح الآن!',
            'message' => "أصبح لديك موعد متاح مع {$doctorName}. لديك 15 دقيقة لتأكيد الحجز قبل انتقاله للشخص التالي.",
        ]);
    }
}
