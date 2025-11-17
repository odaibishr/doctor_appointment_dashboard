<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    public function createDay(Request $request)
{
    $validated = $request->validate([
        'day_name' => 'required|string|max:255',
        'short_name' => 'required|string|max:10',
        'day_number' => 'required|integer|min:1|max:7',
    ]);

    $day = Day::create($validated);

    return response()->json([
        'message' => 'Day created successfully',
        'data' => $day
    ], 201);
}
  public function getDay()
{
    $days = Day::orderBy('day_number')->get();

    return response()->json([
        'data' => $days
    ],200);
}
  public function deleteDay($id)
{
    $day = Day::findOrFail($id);
    $day->delete();

    return response()->json([
        'message' => 'Day deleted successfully'
    ]);
}


}
