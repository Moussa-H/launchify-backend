<?php

namespace App\Http\Controllers;


use App\Models\Request;
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



    /**
     * Create or update a mentor.
     */







}
