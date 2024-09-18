<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Startup;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

class DashboardFinanceController extends Controller
{
public function getTotalForCurrentYear()
{
    // Ensure the user is authenticated

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

    // Get the current year
    $currentYear = now()->year;

    // Calculate total incomes for the current year
    $totalIncomes = Income::where('startup_id', $startup->id)
        ->where('year', $currentYear)
        ->selectRaw('SUM(product_sales) as total_product_sales')
        ->selectRaw('SUM(service_revenue) as total_service_revenue')
        ->selectRaw('SUM(subscription_fees) as total_subscription_fees')
        ->selectRaw('SUM(investment_income) as total_investment_income')
        ->first();

    $totalIncomesSum = $totalIncomes->total_product_sales +
                       $totalIncomes->total_service_revenue +
                       $totalIncomes->total_subscription_fees +
                       $totalIncomes->total_investment_income;

    // Calculate total expenses for the current year
    $totalExpenses = Expense::where('startup_id', $startup->id)
        ->where('year', $currentYear)
        ->selectRaw('SUM(office_rent) as total_office_rent')
        ->selectRaw('SUM(marketing) as total_marketing')
        ->selectRaw('SUM(legal_accounting) as total_legal_accounting')
        ->selectRaw('SUM(maintenance) as total_maintenance')
        ->selectRaw('SUM(software_licenses) as total_software_licenses')
        ->selectRaw('SUM(office_supplies) as total_office_supplies')
        ->selectRaw('SUM(miscellaneous) as total_miscellaneous')
        ->first();

    $totalExpensesSum = $totalExpenses->total_office_rent +
                        $totalExpenses->total_marketing +
                        $totalExpenses->total_legal_accounting +
                        $totalExpenses->total_maintenance +
                        $totalExpenses->total_software_licenses +
                        $totalExpenses->total_office_supplies +
                        $totalExpenses->total_miscellaneous;

    // Add total salaries for the current year to the expenses
    $totalSalaries = TeamMember::where('startup_id', $startup->id)
        ->sum('salary'); // Assuming 'salary' column is available in the TeamMember model

    $totalExpensesSum += $totalSalaries; // Add salaries to total expenses

    // Calculate savings
    $savings = $totalIncomesSum - $totalExpensesSum;

    return response()->json([
        'status' => 'success',
        'message' => 'Totals for the current year retrieved successfully',
        'total_incomes_sum' => $totalIncomesSum,
        'total_expenses_sum' => $totalExpensesSum,
        'savings' => $savings,
        'year' => $currentYear,
    ], 200);
}


public function ExpensesTable(Request $request)
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

    // Calculate total salaries for team members updated within the given year and month
    $totalSalaries = TeamMember::where('startup_id', $startup->id)
        ->whereYear('updated_at', $validatedData['year'])  // Filter by year
        ->whereMonth('updated_at', $validatedData['month']) // Filter by month
        ->sum('salary');

    // Check if expenses are found, if not return a message
    if ($expenses->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'No expenses found for the given date',
            'data' => [
                'expenses' => [],
                'total_salaries' => $totalSalaries, // Include total salaries even if no expenses found
            ],
        ], 200);
    }

    // Return the expenses and total salaries with a proper structure
    return response()->json([
        'status' => 'success',
        'message' => 'Expenses and total salaries retrieved successfully',
        'data' => [
            'expenses' => $expenses,
            'total_salaries' => $totalSalaries, // Include total salaries in the response
        ],
    ], 200);
}







}
