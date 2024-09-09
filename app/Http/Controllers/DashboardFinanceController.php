<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Startup;
use Illuminate\Http\Request;

class DashboardFinanceController extends Controller
{
   public function getTotalForCurrentYear()
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






public function getMonthlyBreakdown(Request $request)
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

    // Validate the request for year parameter
    $validatedData = $request->validate([
        'year' => 'required|integer',
    ]);

    $year = $validatedData['year'];

    // Initialize arrays to store total incomes and expenses by month
    $monthlyData = [
        'incomes' => [],
        'expenses' => [],
    ];

    // Fetch total incomes by month
    $monthlyIncomes = Income::where('startup_id', $startup->id)
        ->where('year', $year)
        ->selectRaw('month, 
            SUM(product_sales) as total_product_sales,
            SUM(service_revenue) as total_service_revenue,
            SUM(subscription_fees) as total_subscription_fees,
            SUM(investment_income) as total_investment_income')
        ->groupBy('month')
        ->get();

    foreach ($monthlyIncomes as $income) {
        $monthlyData['incomes'][$income->month] = [
            'total_product_sales' => $income->total_product_sales,
            'total_service_revenue' => $income->total_service_revenue,
            'total_subscription_fees' => $income->total_subscription_fees,
            'total_investment_income' => $income->total_investment_income,
        ];
    }

    // Fetch total expenses by month
    $monthlyExpenses = Expense::where('startup_id', $startup->id)
        ->where('year', $year)
        ->selectRaw('month, 
            SUM(office_rent) as total_office_rent,
            SUM(marketing) as total_marketing,
            SUM(legal_accounting) as total_legal_accounting,
            SUM(maintenance) as total_maintenance,
            SUM(software_licenses) as total_software_licenses,
            SUM(office_supplies) as total_office_supplies,
            SUM(miscellaneous) as total_miscellaneous')
        ->groupBy('month')
        ->get();

    foreach ($monthlyExpenses as $expense) {
        $monthlyData['expenses'][$expense->month] = [
            'total_office_rent' => $expense->total_office_rent,
            'total_marketing' => $expense->total_marketing,
            'total_legal_accounting' => $expense->total_legal_accounting,
            'total_maintenance' => $expense->total_maintenance,
            'total_software_licenses' => $expense->total_software_licenses,
            'total_office_supplies' => $expense->total_office_supplies,
            'total_miscellaneous' => $expense->total_miscellaneous,
        ];
    }

    return response()->json([
        'status' => 'success',
        'message' => "Monthly breakdown for year $year retrieved successfully",
        'data' => $monthlyData,
    ], 200);
}



}
