<?php

namespace App\Console\Commands;

use App\Models\DoctorWaitlist;
use App\Models\Notification;
use Illuminate\Console\Command;

class ExpireWaitlistNotifications extends Command
{
    protected $signature = 'waitlist:expire-notifications';
    protected $description = 'Expire waitlist notifications that have passed their deadline and notify next person';

    public function handle(): int
    {
        $expiredWaitlists = DoctorWaitlist::where('status', 'notified')
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;

        foreach ($expiredWaitlists as $waitlist) {
            $waitlist->markAsExpired();

            $nextInWaitlist = DoctorWaitlist::where('doctor_id', $waitlist->doctor_id)
                ->where('status', 'waiting')
                ->orderedByPosition()
                ->first();

            if ($nextInWaitlist) {
                $nextInWaitlist->markAsNotified(15);

                $doctorName = $waitlist->doctor?->name ?? 'الطبيب';

                Notification::create([
                    'user_id' => $nextInWaitlist->user_id,
                    'title' => '🎉 موعد متاح الآن!',
                    'message' => "أصبح لديك موعد متاح مع {$doctorName}. لديك 15 دقيقة لتأكيد الحجز قبل انتقاله للشخص التالي.",
                ]);
            }

            $count++;
        }

        $this->info("Expired {$count} waitlist notifications.");

        return Command::SUCCESS;
    }
}
