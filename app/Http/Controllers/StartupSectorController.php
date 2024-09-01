<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Sector;
use App\Models\StartupSector;

class StartupSectorController extends Controller
{
  
    public function getSectors($startupId)
    {
        $startup = Startup::findOrFail($startupId);
        return response()->json($startup->sectors);
    }

    // Add sectors to a startup
    public function addSectors(Request $request, $startupId)
    {
        $startup = Startup::findOrFail($startupId);
        $sectorIds = $request->input('sector_ids'); // Expecting an array of sector IDs
        
        // Attach sectors to the startup
        $startup->sectors()->syncWithoutDetaching($sectorIds);

        return response()->json(['message' => 'Sectors added successfully', 'sectors' => $startup->sectors], 201);
    }

    // Update sectors for a startup (replace all existing sectors with new ones)
    public function updateSectors(Request $request, $startupId)
    {
        $startup = Startup::findOrFail($startupId);
        $sectorIds = $request->input('sector_ids'); // Expecting an array of sector IDs

        // Sync the sectors (this will detach all current sectors and attach the new ones)
        $startup->sectors()->sync($sectorIds);

        return response()->json(['message' => 'Sectors updated successfully', 'sectors' => $startup->sectors], 200);
    }

    // Remove a specific sector from a startup
    public function removeSector($startupId, $sectorId)
    {
        $startup = Startup::findOrFail($startupId);
        $startup->sectors()->detach($sectorId);

        return response()->json(['message' => 'Sector removed successfully'], 200);
    }
}
