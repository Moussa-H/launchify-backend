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

   

  

   
}
