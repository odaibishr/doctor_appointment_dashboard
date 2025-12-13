<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function create(Request $request)
    {
       $valdiate_data= $request->validate([
        'name'      => 'required|string|max:255',
        'icon'      => 'nullable|string',
        'is_active' => 'boolean',
    ]);
    $specialty=Specialty::create($valdiate_data);
    return response()->json([
        'message' => 'Specialty created successfully',
        'data' => $specialty
    ], 201);

    }

   public function update(Request $request, $id)
        {
            $specialty = Specialty::findOrFail($id);

            $request->validate([
                'name'      => 'sometimes|string|max:255',
                'icon'      => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $specialty->update([
                'name'=> $request->name ?? $specialty->name,
                'icon'=> $request->icon ?? $specialty->icon,
                'is_active' => $request->is_active ?? $specialty->is_active,
            ]);

            return response()->json([
                'message' => 'Specialty updated successfully',
                'data' => $specialty
            ],200);
        }


    public function delete($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();

        return response()->json([
            'message' => 'Specialty deleted successfully'
        ]);
    }

    public function index()
    {

        $specialty = Specialty::query()
            ->where('is_active', true)
            ->withCount('doctors')
            ->get();
        return response()->json(
            ['data' => $specialty], 200
        );
    }


}
