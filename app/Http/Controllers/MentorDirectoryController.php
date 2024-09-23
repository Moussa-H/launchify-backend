<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MentorDirectoryController extends Controller
{
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
        $request = DB::table('requests')
                    ->where('startup_id', $startup->id)
                    ->where('mentor_id', $mentor->id)
                    ->first();

        // If a request exists, attach the status, otherwise set it to 'none'
        $status = $request ? $request->status : 'none';

        // Return the mentor's data along with the status
        return [
            'id' => $mentor->id,
            'user_id' => $mentor->user_id,
            'full_name' => $mentor->full_name,
            'industry' => $mentor->industry,
            'expertise' => $mentor->expertise,
            'description' => $mentor->description,
            'phone_number' => $mentor->phone_number,
            'location' => $mentor->location,
            'image_url' => $mentor->image_url,
            'created_at' => $mentor->created_at,
            'updated_at' => $mentor->updated_at,
            'status' => $status,  // Add the status here
        ];
    });

    // Return the mentors with their status
    return response()->json([
        'status' => 'success',
        'mentors' => $mentorsWithStatus,
        'startupId'=>$startup->id,
    ], 200);
}


}
