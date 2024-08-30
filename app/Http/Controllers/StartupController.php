<?php

namespace App\Http\Controllers;
use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        $startups = Startup::all();
        return view('startups.index', compact('startups'));
    }

    public function show($id)
    {
        $startup = Startup::findOrFail($id);
        return view('startups.show', compact('startup'));
    }

    public function create()
    {
        return view('startups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'website_url' => 'nullable|string|url',
            'currently_raising_type' => 'nullable|in:Founders,Family & Friends,Pre-seed,Seed,Pre-series A,Series A',
            'currently_raising_size' => 'nullable|numeric',
        ]);

        $startup = Startup::create($data);

        // Handle sectors, investment sources, and team members if provided

       return response()->json($startup);
    }
}
