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
    public function addInvestmentSources(Request $request, $startupId)
    {
        $startup = Startup::findOrFail($startupId);
        $investmentSources = $request->input('investment_sources'); // Expecting an array of investment sources

        foreach ($investmentSources as $source) {
            $startup->investmentSources()->create($source);
        }

        return response()->json(['message' => 'Investment sources added successfully', 'investmentSources' => $startup->investmentSources], 201);
    }

    // Update investment sources for a startup (replace all existing sources with new ones)
      public function updateInvestmentSources(Request $request, $startupId)
    {
        $startup = Startup::findOrFail($startupId);
        $investmentSources = $request->input('investment_sources'); // Expecting an array of investment sources

        $startup->investmentSources()->delete();

        foreach ($investmentSources as $source) {
            $startup->investmentSources()->create($source);
        }

        return response()->json(['message' => 'Investment sources updated successfully', 'investmentSources' => $startup->investmentSources], 200);
    }
    // Remove a specific investment source from a startup
    public function removeInvestmentSource($startupId, $investmentSourceId)
    {
        $startup = Startup::findOrFail($startupId);
        $startup->investmentSources()->where('id', $investmentSourceId)->delete();

        return response()->json(['message' => 'Investment source removed successfully'], 200);
    }
}
