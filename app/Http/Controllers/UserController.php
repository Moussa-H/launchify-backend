<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
 public function index()
    {
        // Retrieve all users
        $users = User::all();

        // Return the users as a JSON response
        return response()->json($users);
    }
}
