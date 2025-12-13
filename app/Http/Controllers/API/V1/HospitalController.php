<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Location;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function createHospital(Request $request)
    {
        $valdiate_data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'website' => 'nullable|url',
            'address' => 'required|string',
            'image' => 'nullable|sring',
            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
            'location.name' => 'nullable|string',
        ]);
        $location_data = Location::create([
            'lat' => $valdiate_data['location']['lat'],
            'lng' => $valdiate_data['location']['lng'],
            'name' => $valdiate_data['location']['name'] ?? null,
        ]);
        $hospital = Hospital::create([
            'name' => $valdiate_data['name'],
            'phone' => $valdiate_data['phone'],
            'email' => $valdiate_data['email'],
            'address' => $valdiate_data['address'],
            'location_id' => $location_data->id,
        ]);


        $hospital->save();
        $hospital->load('location');
        return response()->json([
            'message' => 'hospital created successfully',
            'data' => $hospital
        ], 201);

    }

    public function getHospitalDetails($id)
    {
        $hospital = Hospital::with('location', 'doctors')->findOrFail($id);
        if (!$hospital) {
            return response()->json([
                'message' => 'Hospital not found'
            ], 404);
        }

        return response()->json([
            'data' => $hospital
        ], 200);
    }

    public function updateHospital(Request $request, $id)
    {

        $hospital = Hospital::with('location')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email|unique:hospitals,email,' . $hospital->id,
            'address' => 'sometimes|string',

            //location data
            'location.lat' => 'sometimes|numeric',
            'location.lng' => 'sometimes|numeric',
            'location.name' => 'sometimes|string',
        ]);


        $hospital->update([
            'name' => $validated['name'] ?? $hospital->name,
            'phone' => $validated['phone'] ?? $hospital->phone,
            'email' => $validated['email'] ?? $hospital->email,
            'address' => $validated['address'] ?? $hospital->address,
        ]);

        //update location 
        if (isset($validated['location'])) {
            $hospital->location->update($validated['location']);
        }

        return response()->json([
            'message' => 'Hospital updated successfully',
            'data' => $hospital->load('location')
        ], 200);
    }



    public function deleteHospital($id)
    {
        $hospital = Hospital::with('location')->findOrFail($id);
        $hospital->delete();

        return response()->json([
            'message' => 'Specialty deleted successfully'
        ]);
    }

    public function getAllHospitals()
    {

        $hospital = Hospital::with('location', 'doctors')->get();
        return response()->json(
            ['data' => $hospital],
            200
        );
    }

    public function hospitalSearch(Request $request)
    {
        $name = $request->input('name');
        $hospitalSearch = Hospital::with('location')->where('name', 'LIKE', "%$name%")->get();
        return response()->json([
            'data' => $hospitalSearch
        ]);
    }
}
