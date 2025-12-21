<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FavoriteDoctor;

class DoctorController extends Controller
{
    public function createDoctor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'location_id' => $location->id,
            'role' => User::ROLE_DOCTOR,
            'password' => Hash::make($validated['password']),
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'aboutus' => $validated['aboutus'] ?? null,
            'specialty_id' => $validated['specialty_id'],
            'hospital_id' => $validated['hospital_id'] ?? null,
            'services' => $validated['services'] ?? null,
        ]);


        return response()->json([
            'message' => 'Doctor created successfully',
            'data' => $doctor->load(['user.location', 'specialty', 'hospital'])
        ], 201);
    }
    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . ($user?->id ?? 'NULL'),
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
            $locationData = $validated['location'] ?? [];

            if ($user?->location) {
                $user->location->update($locationData);
            } else {
                $location = Location::create($locationData);
                $user?->update(['location_id' => $location->id]);
            }
        }


        if ($user) {
            $user->update(array_filter([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ], fn($v) => $v !== null));
        }

        $doctor->update(array_filter([
            'aboutus' => $validated['aboutus'] ?? null,
            'specialty_id' => $validated['specialty_id'] ?? null,
            'hospital_id' => $validated['hospital_id'] ?? null,
            'services' => $validated['services'] ?? null,
        ], fn($v) => $v !== null));

        return response()->json([
            'message' => 'Doctor updated successfully',
            'data' => $doctor->load(['user.location', 'specialty', 'hospital'])
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

        $doctors = Doctor::with(['user', 'specialty', 'hospital'])
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
            ->with(['user', 'specialty', 'hospital'])
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
                    $subQ->whereHas('user', function ($q3) use ($query) {
                        $q3->where('name', 'LIKE', "%$query%");
                    })
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
            ->with(['user', 'specialty', 'hospital', 'schedules.day', 'daysOff.day'])
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
