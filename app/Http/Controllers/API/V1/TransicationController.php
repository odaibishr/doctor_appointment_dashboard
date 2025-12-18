<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransicationController extends Controller
{
    public function createTranscation(Request $request)
    {

        $user = auth()->user()->id;
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Make sure to send Bearer token.'
            ], 401);
        }



        $request->validate([
            'amount' => 'required|numeric|min:0.01',

        ]);


        $transaction = Transaction::create([
            'user_id' => $user,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ]);
    }


    public function deleteTranscation($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction deleted successfully'
        ]);
    }
}
