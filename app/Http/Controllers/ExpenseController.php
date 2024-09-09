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
    public function store(Request $request)
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

        // Fetch the user's startup, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the request data except 'startup_id'
        $validatedData = $request->validate([
            'office_rent' => 'required|integer',
            'marketing' => 'required|integer',
            'legal_accounting' => 'required|integer',
            'maintenance' => 'required|integer',
            'software_licenses' => 'required|integer',
            'office_supplies' => 'required|integer',
            'miscellaneous' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Check if the expense for the same month and year exists, then update or create
        $expense = Expense::updateOrCreate(
            [
                'startup_id' => $startup->id,
                'year' => $validatedData['year'],
                'month' => $validatedData['month']
            ],
            $validatedData
        );

        return response()->json($expense, 201);
    }

    // Update an existing expense
    public function update(Request $request)
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

        // Fetch the user's startup, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the request data
        $validatedData = $request->validate([
            'office_rent' => 'required|integer',
            'marketing' => 'required|integer',
            'legal_accounting' => 'required|integer',
            'maintenance' => 'required|integer',
            'software_licenses' => 'required|integer',
            'office_supplies' => 'required|integer',
            'miscellaneous' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Fetch the existing expense or fail if not found
        $expense = Expense::where('startup_id', $startup->id)
            ->where('year', $validatedData['year'])
            ->where('month', $validatedData['month'])
            ->firstOrFail();

        // Update the expense
        $expense->update($validatedData);

        return response()->json($expense, 200);
    }

    // Delete an expense
    public function destroy(Request $request)
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

        // Fetch the user's startup, throw 404 if not found
        $startup = Startup::where('user_id', $userId)->firstOrFail();

        // Validate the request for year and month
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Fetch the existing expense or fail if not found
        $expense = Expense::where('startup_id', $startup->id)
            ->where('year', $validatedData['year'])
            ->where('month', $validatedData['month'])
            ->firstOrFail();

        // Delete the expense
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully.'], 200);
    }










}
