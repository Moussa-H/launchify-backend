<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Investor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestorController extends Controller
{
    public function index()
    {
        return Investor::all();
    }

    public function show($id)
    {
        $investor = Investor::find($id);

        if (!$investor) {
            return response()->json(['message' => 'Investor not found'], 404);
        }

        return response()->json($investor, 200);
    }


   public function getInvestor(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Get the authenticated user ID
    $user_id = Auth::id();

    // Validate the user_id parameter (optional, but useful for consistency)
    $validator = Validator::make(['user_id' => $user_id], [
        'user_id' => 'required|numeric|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid user ID',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Fetch investors associated with the user ID
    $investors = Investor::where('user_id', $user_id)
        ->get()
        ->map(function ($investor) {
            // Optionally, you can include additional processing here if needed
            return $investor;
        });

    if ($investors->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No investors found for this user',
        ]);
    }

    // Return the investor data
    return response()->json([
        'status' => 'success',
        'investors' => $investors,
    ], 200);
}






}