<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Startup;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
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


  



}
