<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // Get expenses by year and month
    public function index($year, $month)
    {
        $expenses = Expense::where('year', $year)
                            ->where('month', $month)
                            ->get();

        return response()->json($expenses);
    }

     public function store(Request $request)
    {
        $validatedData = $request->validate([
            'startup_id' => 'required|exists:startups,id',
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

        $expense = Expense::updateOrCreate(
            ['startup_id' => $validatedData['startup_id'], 'year' => $validatedData['year'], 'month' => $validatedData['month']],
            $validatedData
        );

        return response()->json($expense, 201);
    }

}
