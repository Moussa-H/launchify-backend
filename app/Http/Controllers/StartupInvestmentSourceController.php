<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\StartupInvestmentSource;

class StartupInvestmentSourceController extends Controller
{
    // Get investment sources for a specific startup
    public function getInvestmentSources($startupId)
    {
        $startup = Startup::findOrFail($startupId);
        return response()->json($startup->investmentSources);
    }

    // Add investment sources to a startup
  public function createOrUpdateInvestmentSources(Request $request, $startupId)
{
    // Find the startup or fail
    $startup = Startup::findOrFail($startupId);

    // Get the investment sources from the request
    $investmentSources = $request->input('investment_sources'); // Expecting an array of investment sources

    // Validate that the investment sources is an array
    if (!is_array($investmentSources)) {
        return response()->json(['message' => 'Invalid investment sources format'], 400);
    }

    // Begin a transaction to ensure atomicity
    \DB::beginTransaction();
    try {
        // Delete all existing investment sources
        $startup->investmentSources()->delete();

        // Create or update investment sources
        foreach ($investmentSources as $source) {
            $startup->investmentSources()->create($source);
        }

        // Commit the transaction
        \DB::commit();

        return response()->json([
            'message' => 'Investment sources created/updated successfully',
            'investmentSources' => $startup->investmentSources
        ], 200);
    } catch (\Exception $e) {
        // Rollback the transaction on error
        \DB::rollback();
        return response()->json(['message' => 'Error updating investment sources', 'error' => $e->getMessage()], 500);
    }
}

   public function removeInvestmentSource($startupId)
{
    $startup = Startup::findOrFail($startupId);
    $startup->investmentSources()->delete();

    return response()->json(['message' => 'All investment sources removed successfully'], 200);
}

}
