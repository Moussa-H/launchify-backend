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
public function getAllStartupsInvested(Request $request)
{
    // Ensure user is authenticated
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Validate request inputs for year and month
    $request->validate([
        'year' => 'required|integer|digits:4',   // Year must be a 4-digit integer
        'month' => 'required|integer|min:1|max:12',  // Month must be between 1 and 12
    ]);

    // Get authenticated user's ID
    $userId = Auth::id();

    // Fetch the investor associated with the authenticated user
    $investor = Investor::where('user_id', $userId)->firstOrFail();

    // Fetch all startups where the authenticated user has investments in the requested year and month
    $startups = Startup::whereHas('investments', function($query) use ($investor, $request) {
        $query->where('investor_id', $investor->id)
              ->whereYear('created_at', $request->year)   // Filter by year
              ->whereMonth('created_at', $request->month); // Filter by month
    })->get();

    // Prepare the result data
    $result = $startups->flatMap(function($startup) use ($investor, $request) {
        // Get all investments by this investor for this startup filtered by year and month
        $investments = Investment::where('startup_id', $startup->id)
            ->where('investor_id', $investor->id)
            ->whereYear('created_at', $request->year)   // Filter by year
            ->whereMonth('created_at', $request->month) // Filter by month
            ->get(['amount', 'created_at']); // Fetch amount and date

        // Calculate the total sum of investments for this startup
        $totalInvestment = Investment::where('startup_id', $startup->id)
            ->sum('amount');

        // Calculate the needed investment
        $neededInvestment = $startup->currently_raising_size - $totalInvestment;

        // Return a new array with each investment as a separate entry
        return $investments->map(function($investment) use ($startup, $neededInvestment) {
            return [
                'company_name' => $startup->company_name,
                'needed_investment' => $neededInvestment,
                'amount' => $investment->amount,
                'date' => $investment->created_at->format('Y-m-d'), // Format the date
            ];
        });
    });

    // Return the result as a JSON response
    return response()->json([
        'success' => true,
        'startups' => $result,
    ]);
}



}
