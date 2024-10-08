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
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the startup for the authenticated user, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the request data
        $validatedData = $request->validate([
            'product_sales' => 'required|integer',
            'service_revenue' => 'required|integer',
            'subscription_fees' => 'required|integer',
            'investment_income' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Add the startup ID to the validated data
        $validatedData['startup_id'] = $startup->id;

        // Create or update the income entry
        $income = Income::updateOrCreate(
            [
                'startup_id' => $startup->id, 
                'year' => $validatedData['year'], 
                'month' => $validatedData['month']
            ],
            $validatedData
        );

        return response()->json($income, 201);
    }

    // Update an existing income for the authenticated user's startup
    public function update(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the startup for the authenticated user, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the request data
        $validatedData = $request->validate([
            'product_sales' => 'required|integer',
            'service_revenue' => 'required|integer',
            'subscription_fees' => 'required|integer',
            'investment_income' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Fetch the existing income or throw 404 if not found
        $income = Income::where('startup_id', $startup->id)
            ->where('year', $validatedData['year'])
            ->where('month', $validatedData['month'])
            ->firstOrFail();

        // Update the income entry with the validated data
        $income->update($validatedData);

        return response()->json($income, 200);
    }

    // Delete an income for the authenticated user's startup
    public function destroy(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the startup for the authenticated user, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the year and month query parameters
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Fetch the existing income or throw 404 if not found
        $income = Income::where('startup_id', $startup->id)
            ->where('year', $validatedData['year'])
            ->where('month', $validatedData['month'])
            ->firstOrFail();

        // Delete the income entry
        $income->delete();

        return response()->json(['message' => 'Income deleted successfully'], 200);
    }
}
