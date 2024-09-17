<?php

namespace App\Http\Controllers;
use App\Models\Investor;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;
class DashboardInvestmentController extends Controller
{
      public function getInvestorSummary()
    {
          if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the investor for the authenticated user, throw 404 if not found
        $investor = Investor::where('user_id', $userId)->firstOrFail();

        // Total investment made by the investor
        $totalInvestment = Investment::where('investor_id', $investor->id)
            ->sum('amount');

        // Number of unique startups invested in by the investor
        $startupInvestedCount = Investment::where('investor_id', $investor->id)
            ->distinct('startup_id')
            ->count('startup_id');

        // Total number of startups in the system
        $totalStartups = Startup::count();

        // Return the summary in JSON format
        return response()->json([
            'total_investment' => $totalInvestment,
            'number_of_startups_invested' => $startupInvestedCount,
            'total_startups' => $totalStartups,
        ]);
    
    }

      public function getLastFiveStartups()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the investor for the authenticated user, throw 404 if not found
        $investor = Investor::where('user_id', $userId)->firstOrFail();

        // Get the last 5 startups the investor has invested in
        $startups = Investment::where('investor_id', $investor->id)
            ->join('startups', 'investments.startup_id', '=', 'startups.id')
            ->select('startups.image', 'startups.company_name', 'startups.country', 'investments.amount')
            ->orderBy('investments.created_at', 'desc') // Assuming you want the most recent investments first
            ->limit(5)
            ->get();

        // Return the startups data in JSON format
        return response()->json($startups);
    }



}
