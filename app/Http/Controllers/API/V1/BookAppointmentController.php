<?php


namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\BookAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookAppointmentController extends Controller
{
    public function createAppointment(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_schedule_id' => 'nullable|exists:doctor_schedules,id',
            'date' => 'required|date',
            'payment_mode' => 'required|in:cash,online',
            'status' => 'in:pending,confirmed,cancelled',
            'is_completed' => 'boolean',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $userId = Auth::id();
        $doctorId = $request->doctor_id;

        $existing = BookAppointment::where('user_id', $userId)
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['pending'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have an appointment with this doctor.'
            ], 400);
        }
        $validated['user_id'] = $userId;
        $validated['status'] = 'pending';

        $appointment = BookAppointment::create($validated);
        $appointment->refresh();
        $appointment->load(['doctor.user', 'doctor.specialty', 'doctor.hospital', 'schedule.day', 'transaction']);

        return response()->json([
            'message' => 'Appointment created successfully',
            'data' => $appointment
        ], 201);
    }


    public function deleteAppointment($appointment_id)
    {
        $userId = Auth::id();

        $appointment = BookAppointment::where('id', $appointment_id)
            ->where('user_id', $userId)
            ->first();

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found'
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }

    public function getUserAppointment()
    {
        $userId = Auth::id();

        $appointments = BookAppointment::where('user_id', $userId)
            ->with([
                'doctor.user',
                'doctor.specialty',
                'doctor.hospital',
                'doctor.schedules.day',
                'schedule',
                'transaction'
            ])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'message' => "Appointments retrieved successfully",
            'data' => $appointments
        ]);
    }


    public function updateAppointmentStatus(Request $request, $appointment_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'cancellation_reason' => 'nullable|required_if:status,cancelled|string|max:500',
        ]);

        $appointment = BookAppointment::where('id', $appointment_id)->where('user_id', Auth::id())->first();

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found'
            ], 404);
        }

        $appointment->status = $validated['status'];

        if ($validated['status'] === 'cancelled' && isset($validated['cancellation_reason'])) {
            $appointment->cancellation_reason = $validated['cancellation_reason'];
        }

        $appointment->save();
        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $appointment
        ]);
    }

    public function rescheduleAppointment(Request $request, $appointment_id)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'doctor_schedule_id' => 'nullable|exists:doctor_schedules,id',
        ]);

        $appointment = BookAppointment::where('id', $appointment_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found'
            ], 404);
        }

        if ($appointment->status === 'cancelled') {
            return response()->json([
                'message' => 'Cannot reschedule a cancelled appointment'
            ], 400);
        }

        if ($appointment->is_completed) {
            return response()->json([
                'message' => 'Cannot reschedule a completed appointment'
            ], 400);
        }

        $appointment->date = $validated['date'];
        if (isset($validated['doctor_schedule_id'])) {
            $appointment->doctor_schedule_id = $validated['doctor_schedule_id'];
        }
        $appointment->save();

        return response()->json([
            'message' => 'Appointment rescheduled successfully',
            'data' => $appointment->load(['doctor', 'schedule', 'transaction'])
        ]);
    }
}
