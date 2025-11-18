<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\FavoriteDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteDoctorController extends Controller
{
    public function addFavoriteDoctor(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $userId = Auth::id();

        $exists = FavoriteDoctor::where('user_id', $userId)
            ->where('doctor_id', $validated['doctor_id'])
            ->first();

        if ($exists) {
            return response()->json([
                'message' => 'This doctor is already in favorites'
            ], 409);
        }

        $favorite = FavoriteDoctor::create([
            'user_id' => $userId,
            'doctor_id' => $validated['doctor_id']
        ]);

        return response()->json([
            'message' => 'Doctor added to favorites',
            'data' => $favorite
        ], 201);
    }
    public function deleteFavoriteDoctor($doctor_id)
    {
        $userId = Auth::id();

        $fav = FavoriteDoctor::where('user_id', $userId)
            ->where('doctor_id', $doctor_id)
            ->first();

        if (!$fav) {
            return response()->json([
                'message' => 'Doctor not found in favorites'
            ], 404);
        }

        $fav->delete();

        return response()->json([
            'message' => 'Doctor removed from favorites'
        ], 200);
    }

    public function getUserFavoriteDoctor()
    {
        $userId = Auth::id();

        $favorites = FavoriteDoctor::where('user_id', $userId)
            ->with('doctor')   
            ->get();

        return response()->json([
            'message' => 'Favorites retrieved successfully',
            'data' => $favorites
        ], 200);
    }
}
