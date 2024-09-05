<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Sector;
use Illuminate\Http\JsonResponse;
use App\Models\Startup;

class SectorController extends Controller
{
    /**
     * Get all sectors.
     *
     * @return JsonResponse
     */
    public function getAllSectors(): JsonResponse
    {
        $sectors = Sector::all();
        return response()->json($sectors);
    }

   public function createSectorsForStartup(Request $request, $startupId): JsonResponse
    {
        // Validate the request data
        $request->validate([
            'sector_ids' => 'required|array',
            'sector_ids.*' => 'required|exists:sectors,id',
        ]);

        // Find the startup
        $startup = Startup::findOrFail($startupId);

        // Get the sector IDs from the request
        $sectorIds = $request->input('sector_ids');

        // Sync the startup's sectors
        $startup->sectors()->sync($sectorIds);

        return response()->json(['message' => 'Sectors updated successfully.']);
    }

    /**
     * Get the sectors associated with a startup.
     *
     * @param int $startupId
     * @return JsonResponse
     */
       public function getSectorsByStartup(int $startupId): JsonResponse
    {
        try {
            // Validate the startup ID
            if (!is_numeric($startupId) || $startupId <= 0) {
                return response()->json(['error' => 'Invalid startup ID'], 400);
            }

            // Find the startup
            $startup = Startup::findOrFail($startupId);

            // Get the sectors related to the startup
            $sectors = $startup->sectors()
                ->select('sectors.id', 'sectors.name') // Explicitly select columns from the sectors table
                ->get();

            // Return a success response with startup ID and related sectors
            return response()->json([
                'startup_id' => $startupId,
                'sectors' => $sectors
            ], 200);

        } catch (ModelNotFoundException $e) {
            // Handle the case where the startup is not found
            return response()->json(['error' => 'Startup not found'], 404);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

 
    public function deleteSectorFromStartup(int $startupId, int $sectorId): JsonResponse
    {
        $startup = Startup::findOrFail($startupId);
        $startup->sectors()->detach($sectorId);

        return response()->json(['message' => 'Sector detached successfully']);
    }

       public function updateSectorsForStartup(Request $request, $startupId): JsonResponse
    {
        // Validate the request data
        $request->validate([
            'sector_ids' => 'required|array',
            'sector_ids.*' => 'required|exists:sectors,id',
        ]);

        // Find the startup
        $startup = Startup::findOrFail($startupId);

        // Get the sector IDs from the request
        $sectorIds = $request->input('sector_ids');

        // Sync the startup's sectors
        $startup->sectors()->sync($sectorIds);

        $sectors = $startup->sectors()
            ->select('sectors.id', 'sectors.name') // Explicitly select columns from the sectors table
            ->get();

        return response()->json(['message' => 'Sectors updated successfully.', 'sectors' => $sectors]);
    }
}
