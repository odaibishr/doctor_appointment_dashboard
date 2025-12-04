<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\FavoriteDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteDoctorController extends Controller
{
    public function ToggleFavoriteDoctor(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $userId = Auth::id();

        $exists = FavoriteDoctor::where('user_id', $userId)
            ->where('doctor_id', $validated['doctor_id'])
            ->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['message' => 'تم إزالة الطبيب من المفضلة'], 200);
        }

        $favorite = FavoriteDoctor::create([
            'user_id' => $userId,
            'doctor_id' => $validated['doctor_id']
        ]);

        return response()->json([
            'message' => 'تم إضافة الطبيب للمفضلة',
            'data' => $favorite
        ], 201);
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
