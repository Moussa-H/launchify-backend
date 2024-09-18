<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Models\Mentor;
use Illuminate\Http\Request;

class MatchingController extends Controller
{
   public function match(Request $request)
    {
        $response = Http::post(config('ml.url') . '/match', $request->all());

        return response()->json($response->json());
    }
}
