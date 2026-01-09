<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\BookAppointment;
use App\Models\Doctor;
use App\Models\DoctorWaitlist;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaitlistController extends Controller
{
    public function join(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_schedule_id' => 'nullable|exists:doctor_schedules,id',
        ]);

        $userId = Auth::id();
        $doctorId = $validated['doctor_id'];

        $existingWaitlist = DoctorWaitlist::where('user_id', $userId)
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['waiting', 'notified'])
            ->first();

        if ($existingWaitlist) {
            return response()->json([
                'success' => false,
                'message' => 'أنت بالفعل في قائمة الانتظار لهذا الطبيب',
                'data' => $existingWaitlist,
            ], 400);
        }

        $position = DoctorWaitlist::where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->count() + 1;

        $waitlist = DoctorWaitlist::create([
            'user_id' => $userId,
            'doctor_id' => $doctorId,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_schedule_id' => $validated['preferred_schedule_id'] ?? null,
            'status' => 'waiting',
            'position' => $position,
        ]);

        $waitlist->load(['doctor', 'preferredSchedule']);

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافتك لقائمة الانتظار بنجاح',
            'data' => [
                'waitlist' => $waitlist,
                'position' => $position,
            ],
        ], 201);
    }

    public function leave(int $id): JsonResponse
    {
        $waitlist = DoctorWaitlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['waiting', 'notified'])
            ->first();

        if (!$waitlist) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على قائمة الانتظار',
            ], 404);
        }

        $waitlist->markAsCancelled();

        $this->reorderPositions($waitlist->doctor_id);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالتك من قائمة الانتظار بنجاح',
        ]);
    }

    public function myWaitlists(): JsonResponse
    {
        $waitlists = DoctorWaitlist::where('user_id', Auth::id())
            ->whereIn('status', ['waiting', 'notified'])
            ->with(['doctor', 'preferredSchedule'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب قوائم الانتظار بنجاح',
            'data' => $waitlists,
        ]);
    }

    public function getPosition(int $doctorId): JsonResponse
    {
        $waitlist = DoctorWaitlist::where('user_id', Auth::id())
            ->where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->first();

        if (!$waitlist) {
            return response()->json([
                'success' => false,
                'message' => 'أنت لست في قائمة الانتظار لهذا الطبيب',
                'data' => ['in_waitlist' => false],
            ]);
        }

        $position = DoctorWaitlist::where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->where('created_at', '<', $waitlist->created_at)
            ->count() + 1;

        return response()->json([
            'success' => true,
            'message' => 'تم جلب ترتيبك في القائمة',
            'data' => [
                'in_waitlist' => true,
                'position' => $position,
                'waitlist' => $waitlist,
            ],
        ]);
    }

    public function acceptSlot(Request $request, int $id): JsonResponse
    {
        $waitlist = DoctorWaitlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'notified')
            ->first();

        if (!$waitlist) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على الموعد المتاح أو انتهت صلاحيته',
            ], 404);
        }

        if ($waitlist->isExpired()) {
            $waitlist->markAsExpired();
            $this->notifyNextInQueue($waitlist->doctor_id);

            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية هذا العرض',
            ], 400);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'payment_mode' => 'required|in:cash,online',
        ]);

        DB::beginTransaction();

        $appointment = BookAppointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $waitlist->doctor_id,
            'doctor_schedule_id' => $validated['doctor_schedule_id'],
            'date' => $validated['date'],
            'payment_mode' => $validated['payment_mode'],
            'status' => 'pending',
        ]);

        $waitlist->markAsBooked();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم حجز الموعد بنجاح',
            'data' => $appointment->load(['doctor', 'schedule']),
        ], 201);
    }

    public function declineSlot(int $id): JsonResponse
    {
        $waitlist = DoctorWaitlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'notified')
            ->first();

        if (!$waitlist) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على الموعد المتاح',
            ], 404);
        }

        $doctorId = $waitlist->doctor_id;
        $waitlist->markAsCancelled();

        $this->notifyNextInQueue($doctorId);

        return response()->json([
            'success' => true,
            'message' => 'تم رفض الموعد وإبلاغ الشخص التالي في القائمة',
        ]);
    }

    public function checkDoctorAvailability(int $doctorId): JsonResponse
    {
        $doctor = Doctor::with('schedules')->find($doctorId);

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'الطبيب غير موجود',
            ], 404);
        }

        $hasAvailableSlots = $this->doctorHasAvailableSlots($doctorId);

        $userInWaitlist = DoctorWaitlist::where('user_id', Auth::id())
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['waiting', 'notified'])
            ->first();

        $waitlistCount = DoctorWaitlist::where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'has_available_slots' => $hasAvailableSlots,
                'can_join_waitlist' => !$hasAvailableSlots && !$userInWaitlist,
                'user_in_waitlist' => $userInWaitlist !== null,
                'user_waitlist_entry' => $userInWaitlist,
                'waitlist_count' => $waitlistCount,
            ],
        ]);
    }

    private function reorderPositions(int $doctorId): void
    {
        $waitlists = DoctorWaitlist::where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->get();

        foreach ($waitlists as $index => $waitlist) {
            $waitlist->update(['position' => $index + 1]);
        }
    }

    private function notifyNextInQueue(int $doctorId): void
    {
        $nextWaitlist = DoctorWaitlist::where('doctor_id', $doctorId)
            ->where('status', 'waiting')
            ->orderedByPosition()
            ->first();

        if (!$nextWaitlist) {
            return;
        }

        $nextWaitlist->markAsNotified(15);

        $doctor = Doctor::find($doctorId);
        $doctorName = $doctor?->name ?? 'الطبيب';

        Notification::create([
            'user_id' => $nextWaitlist->user_id,
            'title' => '🎉 موعد متاح الآن!',
            'message' => "أصبح لديك موعد متاح مع {$doctorName}. لديك 15 دقيقة لتأكيد الحجز قبل انتقاله للشخص التالي.",
        ]);
    }

    private function doctorHasAvailableSlots(int $doctorId): bool
    {
        return true;
    }
}
