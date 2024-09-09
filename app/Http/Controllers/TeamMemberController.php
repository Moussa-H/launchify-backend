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

  

  

 
}
