<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
     public function createLocation(Request $request)
    {
        $validate_date=$request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'name' => 'nullable|string',
        ]);

        $location = Location::create($validate_date);

        return response()->json([
            'message' => 'Location created successfully',
            'data' => $location
        ],201);
    }

    public function updateLocation(Request $request,$id)
    {   $location=Location::findOrFail($id);
        $validate_date=$request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'name' => 'nullable|string',
        ]);
        $update_location=$location->update( $validate_date);
        return response()->json([
            'message'=>'Location updated successfully',
            'data'=>$update_location
        ],200);
    }

    public function deleteLocation($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['message' => 'Location deleted']);
    }
}
