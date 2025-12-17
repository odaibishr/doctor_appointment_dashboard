<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'location_id' => 'required|exists:locations,id',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var User|null $patient */
        $patient = auth()->user();

        $imagePath = null;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')
                ->store('patients', 'public');
        }

        if (! $patient) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $patient->update([
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'location_id' => (int) $request->location_id,
            'profile_image' => $imagePath ?? $patient->profile_image,
            'role' => $patient->roleNormalized() ?: User::ROLE_PATIENT,
        ]);

        return response()->json([
            'message' => 'تم إنشاء بيانات المريض بنجاح',
            'data' => $patient,
        ], 201);
    }

    public function getPatientData(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $patient = User::query()->with('location')->find($user->id);

            if (!$patient) {
                return response()->json([
                    'message' => 'لم يتم العثور على بيانات المريض',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'message' => 'تم الحصول على بيانات المريض بنجاح',
                'data' => $patient,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ داخلي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
