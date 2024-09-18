<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Investor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestorController extends Controller
{
    public function index()
    {
        return Investor::all();
    }

    public function show($id)
    {
        $investor = Investor::find($id);

        if (!$investor) {
            return response()->json(['message' => 'Investor not found'], 404);
        }

        return response()->json($investor, 200);
    }







}