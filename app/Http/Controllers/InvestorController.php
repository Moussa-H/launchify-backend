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



public function createOrUpdateInvestor(Request $request, $id = null)
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

    // Determine if this is an update or create operation
    if ($id) {
        // Find the investor by ID
        $investor = Investor::find($id);

        // Check if the investor exists and if it belongs to the authenticated user for update
        if (!$investor || $investor->user_id != $user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Investor not found or not owned by the user',
            ], 404);
        }
    } else {
        // Prepare for creating a new investor
        $investor = new Investor();
        $investor->user_id = $user_id;
    }

    // Validate the request data
    $validated = $request->validate([
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'email' => 'nullable|string|email|max:255',
        'phone_number' => 'nullable|string|max:20',
        'investment_source' => 'nullable|in:Business Angel,Accelerator / Incubator,VC Fund,Corporate,Public grant,Crowd',
        'linkedin_url' => 'nullable|url',
    ]);

    // Assign the validated data to the investor model
    $investor->fill($validated);

    // Save or update the investor
    $investor->save();

    // Return the appropriate response
    $message = $id ? 'Investor updated successfully' : 'Investor created successfully';

    return response()->json([
        'status' => 'success',
        'message' => $message,
        'investor' => $investor,
    ], $id ? 200 : 201);
}


}