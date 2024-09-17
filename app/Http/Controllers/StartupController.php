<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StartupController extends Controller
{
    public function index()
    {
        return Startup::all();
    }

   public function getStartupById($id)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Find the startup by its ID and load related sectors and investment sources
    $startup = Startup::with(['sectors', 'investmentSources']) // Load related sectors and investment sources
        ->find($id);

    // Check if the startup exists
    if (!$startup) {
        return response()->json([
            'status' => 'error',
            'message' => 'Startup not found',
        ], 404);
    }

    // Return the startup data along with related sectors and investment sources
    return response()->json([
        'status' => 'success',
        'startup' => $startup,
    ], 200);
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
        ]);
        

        // Include the user_id from the authenticated user
        $validated['user_id'] = $user->id;

        return Startup::create($validated);
    }

  








}
