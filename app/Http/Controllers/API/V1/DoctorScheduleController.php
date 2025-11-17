<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function createDoctorSchedule(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_id' => 'required|array',
            'day_id.*' => 'exists:days,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $doctorId  = $validated['doctor_id'];
        $days      = $validated['day_id'];

        DoctorSchedule::where('doctor_id', $doctorId)->delete();

        $insert = [];

        foreach ($days as $dayId) {
            $insert[] = [
                'doctor_id' => $doctorId,
                'day_id' => $dayId,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DoctorSchedule::insert($insert);

        return response()->json([
            'message' => 'Schedule saved',
            'days' => Day::all(),
            'doctor_schedule' => DoctorSchedule::where('doctor_id', $doctorId)->with('day')->get()
        ], 201);
    }

    public function updateDoctorSchedule(Request $request, $doctorId)
    {
        $validated = $request->validate([
            'day_id' => 'required|array',
            'day_id.*' => 'exists:days,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);


        DoctorSchedule::where('doctor_id', $doctorId)->delete();


        foreach ($validated['day_id'] as $dayId) {
            DoctorSchedule::create([
                'doctor_id' => $doctorId,
                'day_id' => $dayId,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
            ]);
        }

        return response()->json([
            'message' => 'Schedule updated successfully',
            'data' => DoctorSchedule::where('doctor_id', $doctorId)->with('day')->get()
        ], 200);
    }
    public function deleteDoctorSchedule($doctorId)
    {
        $count = DoctorSchedule::where('doctor_id', $doctorId)->delete();

        if ($count == 0) {
            return response()->json([
                'message' => 'No schedule found for this doctor'
            ], 404);
        }

        return response()->json([
            'message' => 'Schedule deleted successfully'
        ], 200);
    }


    public function getDoctorSchedule($doctorId)
    {
        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->with('day')
            ->get();

        if ($schedule->isEmpty()) {
            return response()->json([
                'message' => 'No schedule found for this doctor'
            ], 404);
        }

        return response()->json([
            'data' => $schedule
        ], 200);
    }
}
