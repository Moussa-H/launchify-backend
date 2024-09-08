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

   
}
