<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{

    // Get expenses by year and month
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

    // Fetch all expenses for the startup for the given year and month
    $expenses = Expense::where('startup_id', $startup->id)
        ->where('year', $validatedData['year'])
        ->where('month', $validatedData['month'])
        ->get();

    // Check if expenses are found, if not return a message
    if ($expenses->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'No expenses found for the given date',
            'data' => [],
        ], 200);
    }

    // Return the expenses with a proper structure
    return response()->json([
        'status' => 'success',
        'message' => 'Expenses retrieved successfully',
        'data' => $expenses,
    ], 200);
}


    // Store or update an expense
  

    // Update an existing expense
  

    // Delete an expense
  

}
