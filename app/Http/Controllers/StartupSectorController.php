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

   
  public function addSectors(Request $request, $startupId)
{
    $startup = Startup::findOrFail($startupId);
    
    // Retrieve the array of sectors from the request
    $sectors = $request->input('sectors'); // Expecting an array of sector objects
    
    // Check if sectors is provided and is an array
    if (!is_array($sectors)) {
        return response()->json(['message' => 'Invalid sectors data provided'], 400);
    }

    // Loop through the sectors array and attach the sectors to the startup
    foreach ($sectors as $sector) {
        if (isset($sector['id'])) {
            $startup->sectors()->attach($sector['id']); // Assuming you're attaching existing sector IDs
        }
    }

    return response()->json(['message' => 'Sectors added successfully', 'sectors' => $startup->sectors], 201);
}


    // Update sectors for a startup (replace all existing sectors with new ones)
  public function add_updateSectors(Request $request, $startupId)
{
    // Find the startup by ID, throw 404 if not found
    $startup = Startup::findOrFail($startupId);
    
    // Retrieve the array of sectors from the request
    $sectors = $request->input('sectors'); // Expecting an array of sector objects
    
    // Check if sectors is provided and is an array
    if (!is_array($sectors)) {
        return response()->json(['message' => 'Invalid sectors data provided'], 400);
    }

    // Extract the sector IDs from the sectors array
    $sectorIds = array_map(function ($sector) {
        return $sector['id'];
    }, $sectors);

    // Update the startup's sectors by syncing the provided sector IDs
    // This will detach any sectors not in the provided list and attach new ones
    $startup->sectors()->sync($sectorIds);

    // Return a success response with the updated sectors
    return response()->json(['message' => 'Sectors changed successfully', 'sectors' => $startup->sectors], 200);
}


    // Remove a specific sector from a startup
    public function removeSector($startupId, $sectorId)
    {
        $startup = Startup::findOrFail($startupId);
        $startup->sectors()->detach($sectorId);

        return response()->json(['message' => 'Sector removed successfully'], 200);
    }
}
