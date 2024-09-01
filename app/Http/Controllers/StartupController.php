<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        return Startup::all();
    }

  public function show($id)
    {
        $startup = Startup::find($id);

        if (!$startup) {
            return response()->json(['message' => 'Startup not found'], 404);
        }

        return response()->json($startup, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'founder' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'founding_year' => 'required|integer',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'key_challenges' => 'nullable|string',
            'goals' => 'nullable|string',
            'business_type' => 'required|in:B2B,B2C,B2B2C,B2G,C2C',
            'company_stage' => 'required|in:Idea,Pre-seed,Seed,Early Growth,Growth,Maturity',
            'employees_count' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|string|email|max:255',
            'website_url' => 'nullable|url',
            'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A',
            'currently_raising_size' => 'nullable|numeric',
        ]);

        return Startup::create($validated);
    }

     public function update(Request $request, $id)
    {
        $startup = Startup::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'image' => 'nullable|string',
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'founder' => 'sometimes|string|max:255',
            'industry' => 'sometimes|string|max:255',
            'founding_year' => 'sometimes|integer',
            'country' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'key_challenges' => 'nullable|string',
            'goals' => 'nullable|string',
            'business_type' => 'sometimes|in:B2B,B2C,B2B2C,B2G,C2C',
            'company_stage' => 'sometimes|in:Idea,Pre-seed,Seed,Early Growth,Growth,Maturity',
            'employees_count' => 'nullable|integer',
            'phone_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|string|email|max:255',
            'website_url' => 'nullable|url',
            'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A',
            'currently_raising_size' => 'nullable|numeric',
        ]);

        $startup->update($validated);

        return response()->json($startup, 200);
    }

    public function destroy($id)
    {
        $startup = Startup::findOrFail($id);
        $startup->delete();

        return response()->json(['message' => 'Startup deleted successfully']);
    }
}
