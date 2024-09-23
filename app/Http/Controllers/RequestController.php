<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Startup;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
public function getRequests() 
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    $userId = Auth::id();

    // Fetch the mentor profile for the authenticated user
    $mentor = Mentor::where('user_id', $userId)->firstOrFail();

    // Get all requests where the mentor is involved and the status is 'pending'
    $requests = Request::where('mentor_id', $mentor->id)
                       ->where('status', 'pending') // Add condition for 'pending' status
                       ->get();

    if ($requests->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No pending requests found for this mentor',
        ], 404);
    }

    // Fetch all startups related to these requests
    $startups = Startup::whereIn('id', $requests->pluck('startup_id'))->get();

    // If no startups are found, return an error
    if ($startups->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No startups found',
        ], 404);
    }

    // Return the startup information
    $startupData = $startups->map(function ($startup) {
        return [
            'id' => $startup->id,
            'company_name' => $startup->company_name,
            'description' => $startup->description,
            'country' => $startup->country,
            'sectors' => $startup->sectors ?? 'N/A', // Assuming sectors might be nullable
            'industry' => $startup->industry,
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $startupData,
    ]);
}


    // Send request from Startup to Mentor
   public function sendRequest(HttpRequest $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    $userId = Auth::id();

    // Fetch the user's startup, throw 404 if not found
    $startup = Startup::where('user_id', $userId)->firstOrFail();

    // Validate the mentor_id input
    $validatedData = $request->validate([
        'mentor_id' => 'required|exists:mentors,id',
    ]);

    // Create the request with the authenticated user's startup_id
    $chatRequest = Request::create([
        'startup_id' => $startup->id,  // Use the startup ID fetched earlier
        'mentor_id' => $validatedData['mentor_id'],
        'status' => 'pending',  // Default status is pending
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Request sent successfully',
        'data' => $chatRequest,
    ]);
}


    // Mentor responds to the request (accept or reject)
    public function respondRequest(HttpRequest $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $userId = Auth::id();

        // Fetch the user's startup, throw 404 if not found
        $startup = Mentor::where('user_id', $userId)->firstOrFail();

        $chatRequest = Request::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Update the request status
        $chatRequest->status = $validatedData['status'];
        $chatRequest->save();

        return response()->json(['status' => 'success', 'message' => 'Request status updated', 'data' => $chatRequest]);
    }

    // List all requests (for admin purposes)
    public function index()
    {
        $requests = Request::with(['startup', 'mentor'])->get();

        return response()->json(['status' => 'success', 'data' => $requests]);
    }


    public function sendResponse(HttpRequest $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    $userId = Auth::id();

    // Fetch the user's mentor profile
    $mentor = Mentor::where('user_id', $userId)->firstOrFail();

    // Validate the request input
    $validatedData = $request->validate([
        'startup_id' => 'required|exists:startups,id',
        'status' => 'required|in:pending,accepted,rejected',
    ]);

    // Find the request associated with the mentor and startup
    $chatRequest = Request::where('startup_id', $validatedData['startup_id'])
                          ->where('mentor_id', $mentor->id)
                          ->first();

    // Check if the request exists
    if (!$chatRequest) {
        return response()->json([
            'status' => 'error',
            'message' => 'Request not found',
        ], 404);
    }

    // Update the request status
    $chatRequest->status = $validatedData['status'];
    $chatRequest->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Request status updated successfully',
        'data' => $chatRequest,
    ]);
}

}
