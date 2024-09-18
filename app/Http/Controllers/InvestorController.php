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

 





}