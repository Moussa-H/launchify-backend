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
}
