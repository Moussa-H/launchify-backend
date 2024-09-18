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

    // Delete all existing sectors related to the startup
    $startup->sectors()->detach();

    // Loop through the sectors array and attach the new sectors to the startup
    foreach ($sectors as $sector) {
        if (isset($sector['id'])) {
            $startup->sectors()->attach($sector['id']); // Assuming you're attaching existing sector IDs
        }
    }

    return response()->json(['message' => 'Sectors updated successfully', 'sectors' => $startup->sectors], 201);
}



    // Update sectors for a startup (replace all existing sectors with new ones)
public function createOrUpdateSectors(Request $request, $startupId = null)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Get the authenticated user ID
    $user_id = Auth::id();

    // Determine if this is an update or create operation
    if ($startupId) {
        // Find the startup by ID
        $startup = Startup::find($startupId);

        // Check if the startup exists and if it belongs to the authenticated user
        if (!$startup || $startup->user_id != $user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Startup not found or not owned by the user',
            ], 404);
        }
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Startup ID is required for updating sectors',
        ], 400);
    }

    $sectors = $request->input('sectors'); // Expecting an array of sector objects

    // Check if sectors is provided and is an array
    if (!is_array($sectors)) {
        return response()->json(['message' => 'Invalid sectors data provided'], 400);
    }

    // Extract the sector IDs from the sectors array
    $sectorIds = array_map(function ($sector) {
        return $sector['id'];
    }, $sectors);

    // Log the sector IDs to debug
    \Log::info('Sector IDs to sync:', $sectorIds);

    try {
        // Start transaction to ensure atomicity
        \DB::beginTransaction();

        // Detach all existing sectors related to the startup
        $startup->sectors()->detach();

        // Attach new sectors
        $startup->sectors()->attach($sectorIds);

        // Commit the transaction
        \DB::commit();

        // Return a success response with the updated sectors
        return response()->json([
            'status' => 'success',
            'message' => 'Sectors updated successfully',
            'sectors' => $startup->sectors,
        ], 200);

    } catch (\Exception $e) {
        // Rollback the transaction on error
        \DB::rollBack();

        // Log the exception for debugging
        \Log::error('Error updating sectors:', ['exception' => $e]);

        // Return a generic error response
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update sectors',
        ], 500);
    }
}




    // Remove a specific sector from a startup
    public function removeSector($startupId, $sectorId)
    {
        $startup = Startup::findOrFail($startupId);
        $startup->sectors()->detach($sectorId);

        return response()->json(['message' => 'Sector removed successfully'], 200);
    }
}
