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

   public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'username' => 'required|unique:users|max:255',
        'email' => 'required|unique:users|email|max:255',
        'password' => 'required|min:6',
        'role' => 'required|in:startup,investor,mentor',
    ]);

    // Create a new user
    $user = User::create([
        'username' => $request->input('username'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')), // Encrypt the password
        'role' => $request->input('role'),
    ]);

    // Return the created user as a JSON response
    return response()->json($user, 201);
}



}
