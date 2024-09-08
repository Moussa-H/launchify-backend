<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    // Get incomes by year and month
    public function index($year, $month)
    {
        $incomes = Income::where('year', $year)
                          ->where('month', $month)
                          ->get();

        return response()->json($incomes);
    }

    // Store a new income
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'startup_id' => 'required|exists:startups,id',
            'product_sales' => 'required|integer',
            'service_revenue' => 'required|integer',
            'subscription_fees' => 'required|integer',
            'investment_income' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $income = Income::updateOrCreate(
            ['startup_id' => $validatedData['startup_id'], 'year' => $validatedData['year'], 'month' => $validatedData['month']],
            $validatedData
        );

        return response()->json($income, 201);
    }

  

   
}
