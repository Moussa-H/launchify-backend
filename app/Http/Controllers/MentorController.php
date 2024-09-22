<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Startup;

class MentorController extends Controller
{
    /**
     * Fetch mentors associated with the authenticated user.
     */
    public function getMentor(Request $request)
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

        // Fetch mentors associated with the user ID
        $mentors = Mentor::where('user_id', $user_id)
            ->get();

        if ($mentors->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No mentors found for this user',
            ]);
        }

        // Return the mentor data
        return response()->json([
            'status' => 'success',
            'mentors' => $mentors,
        ], 200);
    }

    public function getAllMentors(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Fetch the authenticated user's startup
    $userId = Auth::id();
    $startup = Startup::where('user_id', $userId)->firstOrFail();

    // Fetch all mentors
    $mentors = Mentor::all();

    if ($mentors->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No mentors found',
        ]);
    }

    // Add status between the startup and mentor to the response
    $mentorsWithStatus = $mentors->map(function($mentor) use ($startup) {
        // Check if there is a request between the startup and the mentor
        $request = Request::where('startup_id', $startup->id)
                          ->where('mentor_id', $mentor->id)
                          ->first();

        // If a request exists, attach the status, otherwise set it to null
        $status = $request ? $request->status : null;

        // Return the mentor's data along with the status
        return [
            'id' => $mentor->id,
            'full_name' => $mentor->full_name,
            'industry' => $mentor->industry,
            'expertise' => $mentor->expertise,
            'phone_number' => $mentor->phone_number,
            'location' => $mentor->location,
            'image_url' => $mentor->image_url,
            'status' => $status, // Add the status here
        ];
    });

    // Return the mentors with their status
    return response()->json([
        'status' => 'success',
        'mentors' => $mentorsWithStatus,
    ], 200);
}


    /**
     * Create or update a mentor.
     */
    public function createOrUpdateMentor(Request $request, $id = null)
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
            // Find the mentor by ID
            $mentor = Mentor::find($id);

            // Check if the mentor exists and if it belongs to the authenticated user for update
            if (!$mentor || $mentor->user_id != $user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mentor not found or not owned by the user',
                ], 404);
            }
        } else {
            // Prepare for creating a new mentor
            $mentor = new Mentor();
            $mentor->user_id = $user_id;
        }

        // Validate the request data
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'expertise' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads', 'public'); // Save to storage/app/public/uploads
            $imageUrl = url('storage/' . $imagePath); // Generate URL
            $validated['image_url'] = $imageUrl;
        }

        // Assign the validated data to the mentor model
        $mentor->fill($validated);

        // Save or update the mentor
        $mentor->save();

        // Return the appropriate response
        $message = $id ? 'Mentor updated successfully' : 'Mentor created successfully';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'mentor' => $mentor,
        ], $id ? 200 : 201);
 
    }




public function getMentorById(Request $request, $id)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Fetch the mentor by ID
    $mentor = Mentor::find($id);

    // Check if the mentor exists
    if (!$mentor) {
        return response()->json([
            'status' => 'error',
            'message' => 'Mentor not found',
        ], 404);
    }

    // Return the mentor's name and industry
    return response()->json([
        'status' => 'success',
        'mentor' => [
            'id' => $mentor->id,
            'full_name' => $mentor->full_name,
            'industry' => $mentor->industry,
        ],
    ], 200);
}

}
