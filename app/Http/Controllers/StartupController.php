<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StartupController extends Controller
{
    public function index()
    {
        return Startup::all();
    }

    public function show($id)
    {
        $startup = Startup::find($id);

        if (!$startup) {
            return response()->json(['message' => 'Startup not found'], 404);
        }

        return response()->json($startup, 200);
    }

    public function store(Request $request)
    {
        $user = $request->attributes->get('user'); // Get the authenticated user

        $validated = $request->validate([
            'image' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'founder' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'founding_year' => 'required|integer',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'key_challenges' => 'nullable|string',
            'goals' => 'nullable|string',
            'business_type' => 'required|in:B2B,B2C,B2B2C,B2G,C2C',
            'company_stage' => 'required|in:Idea,Pre-seed,Seed,Early Growth,Growth,Maturity',
            'employees_count' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|string|email|max:255',
            'website_url' => 'nullable|url',
            'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A',
            'currently_raising_size' => 'nullable|numeric',
        ]);

        // Include the user_id from the authenticated user
        $validated['user_id'] = $user->id;

        return Startup::create($validated);
    }

    public function update(Request $request, $id)
    {
        $user = $request->attributes->get('user'); // Get the authenticated user
        $startup = Startup::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|string',
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'founder' => 'sometimes|string|max:255',
            'industry' => 'sometimes|string|max:255',
            'founding_year' => 'sometimes|integer',
            'country' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'key_challenges' => 'nullable|string',
            'goals' => 'nullable|string',
            'business_type' => 'sometimes|in:B2B,B2C,B2B2C,B2G,C2C',
            'company_stage' => 'sometimes|in:Idea,Pre-seed,Seed,Early Growth,Growth,Maturity',
            'employees_count' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|string|email|max:255',
            'website_url' => 'nullable|url',
            'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A',
            'currently_raising_size' => 'nullable|numeric',
        ]);

        // You can add additional logic to ensure that the user is authorized to update this resource

        $startup->update($validated);

        return response()->json($startup, 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->attributes->get('user'); // Get the authenticated user
        $startup = Startup::findOrFail($id);
        $startup->delete();

        return response()->json(['message' => 'Startup deleted successfully']);
    }


public function getstartup(Request $request)
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

    // Fetch startups associated with the user ID
    $startups = Startup::where('user_id', $user_id)->get();

    if ($startups->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No startups found for this user',
        ], 404);
    }

    // Return the entire startup data
    return response()->json([
        'status' => 'success',
        'startup' => $startups,
    ], 200);
}

}
