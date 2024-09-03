<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\JsonResponse;

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

 public function getSectorsByStartup($startupId)
    {
        // Validate startupId
        if (!is_numeric($startupId)) {
            return response()->json(['error' => 'Invalid startup ID'], 400);
        }

        // Fetch sector IDs related to the startup
        $sectorIds = StartupSector::where('startup_id', $startupId)
            ->pluck('sector_id');

        // Fetch sectors using sector IDs
        $sectors = Sector::whereIn('id', $sectorIds)->get();

        return response()->json($sectors);
    }



}
