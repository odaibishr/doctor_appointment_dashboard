<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\DoctorDaysOff;
use Illuminate\Http\Request;

class DoctorDaysOffController extends Controller
{
    public function createDayOff(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_id' => 'required|array',
            'day_id.*' => 'exists:days,id',
        ]);

        $doctorId = $validated['doctor_id'];
        $days = $validated['day_id'];

        $created = [];

        foreach ($days as $dayId) {

            $exists = DoctorDaysOff::where('doctor_id', $doctorId)
                ->where('day_id', $dayId)
                ->exists();

            if (!$exists) {
                $created[] = DoctorDaysOff::create([
                    'doctor_id' => $doctorId,
                    'day_id' => $dayId,
                ]);
            }
        }

        return response()->json([
            'message' => 'Days off saved successfully',
            'data' => $created
        ], 201);
    }
    public function deleteDay($id)
    {
        $dayOff=DoctorDaysOff::findOrFail($id);
        $dayOff->delete();
        return response()->json([
            'message'=>'day off deleted successfuly'
        ]);
    }
}
