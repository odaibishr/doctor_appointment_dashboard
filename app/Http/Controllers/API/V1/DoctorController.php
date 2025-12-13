<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FavoriteDoctor;

class DoctorController extends Controller
{
    public function createDoctor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string',
            'aboutus' => 'nullable|string',

            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
            'location.name' => 'nullable|string',

            'specialty_id' => 'required|exists:specialties,id',
            'hospital_id' => 'nullable|exists:hospitals,id',

            'gender' => 'required|in:Male,Female',
            'services' => 'nullable|string',
            'password' => 'required|string|min:6'
        ]);

        $location = Location::create($validated['location']);

        $doctor = Doctor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'aboutus' => $validated['aboutus'] ?? null,
            'location_id' => $location->id,
            'specialty_id' => $validated['specialty_id'],
            'hospital_id' => $validated['hospital_id'] ?? null,
            'gender' => $validated['gender'],
            'services' => $validated['services'] ?? null,
            'password' => bcrypt($validated['password']),
        ]);


        return response()->json([
            'message' => 'Doctor created successfully',
            'data' => $doctor->load(['location', 'specialty', 'hospital'])
        ], 201);
    }
    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'sometimes|string',
            'aboutus' => 'sometimes|string',


            'location.lat' => 'sometimes|numeric',
            'location.lng' => 'sometimes|numeric',
            'location.name' => 'sometimes|string',


            'specialty_id' => 'sometimes|exists:specialties,id',
            'hospital_id' => 'sometimes|exists:hospitals,id',

            'gender' => 'sometimes|in:Male,Female',
            'services' => 'sometimes|string',
        ]);


        if ($request->has('location')) {
            $doctor->location->update($validated['location']);
        }


        $doctor->update($validated);

        return response()->json([
            'message' => 'Doctor updated successfully',
            'data' => $doctor->load(['location', 'specialty', 'hospital'])
        ], 200);
    }
    public function deleteDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return response()->json([
            'message' => 'Doctor deleted Successfully'
        ], 200);
    }
    public function AllDoctors()
    {
        $userId = auth()->id();

        $doctors = Doctor::with(['location', 'specialty', 'hospital'])
            ->withCount([
                'favoriteDoctors as is_favorite' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->get();

        return response()->json([
            'data' => $doctors
        ], 200);
    }


    public function getSearchDoctors(Request $request)
    {
        $userId = auth()->id();
        $query = $request->input('query');
        $specialtyId = $request->input('specialty_id');

        $doctors = Doctor::query()
            ->with(['location', 'specialty', 'hospital'])
            ->withCount([
                'favoriteDoctors as is_favorite' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }
            ])

            // Filter by specialty
            ->when($specialtyId, function ($q) use ($specialtyId) {
                $q->where('specialty_id', $specialtyId);
            })

            // Filter by name or hospital name
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->where('name', 'LIKE', "%$query%")
                        ->orWhereHas('hospital', function ($q2) use ($query) {
                            $q2->where('name', 'LIKE', "%{$query}%");
                        });
                });
            })

            ->get();

        return response()->json([
            'data' => $doctors
        ], 200);
    }


    public function getDoctorById($id)
    {
        $userId = auth()->id();

        $doctor = Doctor::query()
            ->with(['location', 'specialty', 'hospital', 'schedules.day', 'daysOff.day'])
            ->withCount([
                'favoriteDoctors as is_favorite' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
            ])
            ->findOrFail($id);

        return response()->json([
            'data' => $doctor,
        ], 200);
    }

}
