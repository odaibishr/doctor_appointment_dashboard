<?php


namespace App\Http\Controllers\API\V1;



use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
     public function index()
    {
        $methods = PaymentMethod::where('is_active', true)->get();

        return response()->json([
            'status' => 'success',
            'methods' => $methods
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|string'
        ]);

        $method = PaymentMethod::create([
            'name' => $request->name,
            'logo' => $request->logo,
            'is_active' => true
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $method
        ]);
    }


    public function updateStatus($id)
    {
        $method = PaymentMethod::find($id);

        if (!$method) {
            return response()->json([
                'status' => 'error',
                'message' => 'Method not found'
            ], 404);
        }

        $method->is_active = !$method->is_active;
        $method->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated',
            'data' => $method
        ]);
    }
}
