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
        $startup = Startup::findOrFail($startupId);
        $teamMembers = $startup->teamMembers;

        return response()->json($teamMembers);
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

 
}
