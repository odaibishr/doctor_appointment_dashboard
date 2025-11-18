<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReviewsController extends Controller
{
    public function createReview(Request $request)
    {
        $validateData=$request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'user_id'=>'requird|exist:users,id',
            'rating'=>'nullable|integer|min:1|max:5',
            'comment'=>'required|string',
        ]);

        $rewiew=Review::create($validateData+['user_id'=>Auth::id()]);
        return response()->json([
            'message' => 'Review created successfully.'
        ]);
    }
    public function deleteReview($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.'
        ]);
    }
    public function getReviewByDoctor($doctorId)
    {
        $reviews = Review::where('doctor_id', $doctorId)
            ->with('user:id,name') 
            ->get();
            
        return response()->json([
            'doctor_id' => $doctorId,
            'count' => $reviews->count(),
            'reviews' => $reviews,
        ]);
    }
}
