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
        ]);
        

        // Include the user_id from the authenticated user
        $validated['user_id'] = $user->id;

        return Startup::create($validated);
    }

  public function type_size_Invest(Request $request, $id){
$startup=Startup::findOrFail($id);
 $validated = $request->validate([
     'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A,Pre-series B,Series B,Series C+',
            'currently_raising_size' => 'nullable|numeric'
 ]);
 $startup->update($validated);
 return response()->json([ 'message' => 'Investment info created/updated successfully', 'data' =>$validated]);
  }



public function deleteTypeSizeInvest($id)
{
    $startup = Startup::findOrFail($id);

    // Set the currently_raising_type and currently_raising_size to null
    $startup->currently_raising_type = null;
    $startup->currently_raising_size = null;

    // Save the updated startup record
    $startup->save();

    return response()->json(['message' => 'Investment type and size deleted successfully']);
}


public function createOrUpdateStartup(Request $request, $id = null)
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
        // Find the startup by ID and ensure it belongs to the authenticated user for update
        $startup = Startup::where('id', $id)->where('user_id', $user_id)->first();
        if (!$startup) {
            return response()->json([
                'status' => 'error',
                'message' => 'Startup not found or not owned by the user',
            ], 404);
        }
    } else {
        // Prepare for creating a new startup
        $startup = new Startup();
        $startup->user_id = $user_id;
    }

    // Validate the request data
    $validated = $request->validate([
        'image' => 'nullable|file', // Accept image filename as string
        'company_name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'founder' => 'nullable|string|max:255',
        'industry' => 'nullable|string|max:255',
        'founding_year' => 'nullable|integer',
        'country' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'key_challenges' => 'nullable|string',
        'goals' => 'nullable|string',
        'business_type' => 'nullable|in:B2B,B2C,B2B2C,B2G,C2C',
        'company_stage' => 'nullable|in:Idea,Pre-seed,Seed,Early Growth,Growth,Maturity',
        'employees_count' => 'nullable|integer',
        'phone_number' => 'nullable|string|max:20',
        'email_address' => 'nullable|string|email|max:255',
        'website_url' => 'nullable|url',
    ]);

    // Handle image field
    if (isset($validated['image'])) {
        $imageName = $validated['image'];

        // Optionally, validate the filename format (e.g., ensure it has a valid extension)
        if (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $imageName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid image file type.',
            ], 400);
        }

        // Construct the full image URL
        $startup->image = url('/storage/uploads/' . $imageName);
    }

    // Assign the rest of the validated data to the startup model
    // Remove 'image' from fillable to prevent overriding
    unset($validated['image']);
    $startup->fill($validated);

    // Save or update the startup
    $startup->save();

    // Return the appropriate response
    $message = $id ? 'Startup updated successfully' : 'Startup created successfully';

    return response()->json([
        'status' => 'success',
        'message' => $message,
        'startup' => $startup,
    ], $id ? 200 : 201);
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

    // Fetch startups associated with the user ID, load related sectors, investment sources, and image URL
    $startups = Startup::where('user_id', $user_id)
        ->with(['sectors', 'investmentSources']) // Load both sectors and investment sources team-members/{startupId}
        ->get()
        ->map(function ($startup) {
            // Generate the full URL for the image if it exists
            if ($startup->image) {
                $startup->image_url = asset('storage/' . $startup->image);
            } else {
                $startup->image_url = null;
            }
            return $startup;
        });

    if ($startups->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No startups found for this user',
        ], 404);
    }

    // Return the startup data along with related sectors, investment sources, and image URL
    return response()->json([
        'status' => 'success',
        'startups' => $startups,
    ], 200);
}



}
