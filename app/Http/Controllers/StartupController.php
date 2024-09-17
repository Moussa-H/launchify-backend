<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StartupController extends Controller
{
    public function index()
    {
        return Startup::all();
    }

   public function getStartupById($id)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Find the startup by its ID and load related sectors and investment sources
    $startup = Startup::with(['sectors', 'investmentSources']) // Load related sectors and investment sources
        ->find($id);

    // Check if the startup exists
    if (!$startup) {
        return response()->json([
            'status' => 'error',
            'message' => 'Startup not found',
        ], 404);
    }

    // Return the startup data along with related sectors and investment sources
    return response()->json([
        'status' => 'success',
        'startup' => $startup,
    ], 200);
}


 
  








}
