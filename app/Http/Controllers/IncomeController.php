<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    // Get incomes by year and month for the authenticated user's startup
 public function index(Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Get the authenticated user's ID
    $userId = Auth::id();

    // Fetch the user's startup, return 404 if not found
    $startup = Startup::where('user_id', $userId)->first();

    if (!$startup) {
        return response()->json([
            'status' => 'error',
            'message' => 'Startup not found',
        ], 404);
    }

    // Validate the query parameters for year and month
    $validatedData = $request->validate([
        'year' => 'required|integer',
        'month' => 'required|integer|min:1|max:12',
    ]);

    // Fetch all incomes for the startup for the given year and month
    $incomes = Income::where('startup_id', $startup->id)
        ->where('year', $validatedData['year'])
        ->where('month', $validatedData['month'])
        ->get();

    // Check if incomes are found, if not return a message
    if ($incomes->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'No incomes found for the given date',
            'data' => [],
        ], 200);
    }

    // Return the incomes with a proper structure
    return response()->json([
        'status' => 'success',
        'message' => 'Incomes retrieved successfully',
        'data' => $incomes,
    ], 200);
}


    // Store or update an income for the authenticated user's startup
  

    // Update an existing income for the authenticated user's startup
   

    // Delete an income for the authenticated user's startup
  
}
