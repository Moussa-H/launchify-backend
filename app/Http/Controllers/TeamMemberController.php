<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\Startup;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{

    // Get all team members for a specific startup
public function index($startupId)
{
    // Attempt to find the startup or return a 404 error if not found
    $startup = Startup::find($startupId);
    
    if (!$startup) {
        // Return a 404 response with a meaningful message if startup is not found
        return response()->json([
            'success' => false,
            'message' => 'Startup not found.',
            'data' => null
        ], 404);
    }
    
    // Check if the startup has any team members
    $teamMembers = $startup->teamMembers;

    if ($teamMembers->isEmpty()) {
        // No team members, return an appropriate response
        return response()->json([
            'success' => true,
            'message' => 'No team members found.',
            'data' => null
        ], 200);
    }

    // If team members exist, return them
    return response()->json([
        'success' => true,
        'message' => 'Team members retrieved successfully.',
        'data' => $teamMembers
    ], 200);
}



    // Store a new team member
    public function store(Request $request, $startupId)
    {
        $startup = Startup::findOrFail($startupId);

        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|integer',
        ]);

        $teamMember = TeamMember::create([
            'startup_id' => $startupId,
            'fullname' => $validatedData['fullname'],
            'position' => $validatedData['position'],
            'salary' => $validatedData['salary'],
        ]);

        return response()->json($teamMember, 201);
    }

    // Update an existing team member
    public function update(Request $request, $startupId, $teamMemberId)
    {
        $startup = Startup::findOrFail($startupId);
        $teamMember = TeamMember::where('startup_id', $startupId)->findOrFail($teamMemberId);

        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|integer',
        ]);

        $teamMember->update($validatedData);

        return response()->json($teamMember);
    }

    // Delete a team member
    public function destroy($startupId, $teamMemberId)
    {
        $startup = Startup::findOrFail($startupId);
        $teamMember = TeamMember::where('startup_id', $startupId)->findOrFail($teamMemberId);

        $teamMember->delete();

        return response()->json(['message' => 'Success']);
    }


       // Get the sum of all team members' salaries for a specific startup
    public function getTotalSalaries($startupId)
    {
        // Find the startup or return a 404 error if not found
        $startup = Startup::find($startupId);
        
        if (!$startup) {
            return response()->json([
                'success' => false,
                'message' => 'Startup not found.',
                'data' => null
            ], 404);
        }

        // Get the sum of all salaries for team members in this startup
        $totalSalaries = TeamMember::where('startup_id', $startupId)->sum('salary');

        return response()->json([
            'success' => true,
            'message' => 'Total salaries calculated successfully.',
            'total_salaries' => $totalSalaries
        ], 200);
    }
}
