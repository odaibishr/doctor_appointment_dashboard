<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookAppointment;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    // اسم الأمر (سطر واحد فقط)
    protected $signature = 'appointments:remind';

    // وصف الأمر (سطر واحد فقط)
    protected $description = 'Send notification before appointment by one hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $from = now()->addHour();
        $to   = now()->addHour()->addMinutes(5);

        $appointments = BookAppointment::with(['user', 'schedule'])
            ->where('is_completed', false)
            ->get();

        foreach ($appointments as $appointment) {

            if (
                !$appointment->user ||
                !$appointment->schedule ||
                !$appointment->user->fcm_token
            ) {
                continue;
            }

            // تكوين وقت الموعد الحقيقي
            $appointmentDateTime = Carbon::parse(
                $appointment->date . ' ' . $appointment->schedule->start_time
            );

            // هل الموعد بعد ساعة؟
            if ($appointmentDateTime->between($from, $to)) {

                app('App\Services\FirebaseNotificationService')
                    ->sendToToken(
                        $appointment->user->fcm_token,
                        'تذكير بالموعد 🩺',
                        'موعدك مع الطبيب بعد ساعة'
                    );
            }
        }

        return Command::SUCCESS;
    }
}
