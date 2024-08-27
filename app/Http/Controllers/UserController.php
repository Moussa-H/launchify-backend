<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        'role' => 'required|in:Startup,Investor,Mentor',
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

public function update(Request $request, $id)
{
    // Find the user by ID
    $user = User::find($id);

    // If user not found, return a 404 response
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Validate the request data
    $request->validate([
        'username' => 'sometimes|required|max:255|unique:users,username,' . $id,
        'email' => 'sometimes|required|email|max:255|unique:users,email,' . $id,
        'password' => 'sometimes|required|min:6',
        'role' => 'sometimes|required|in:Startup,Investor,Mentor',
    ]);

    // Update user fields
    $user->username = $request->input('username', $user->username);
    $user->email = $request->input('email', $user->email);
    if ($request->has('password')) {
        $user->password = bcrypt($request->input('password')); // Encrypt the password
    }
    $user->role = $request->input('role', $user->role);
    $user->save();

    // Return the updated user as a JSON response
    return response()->json($user);
}
public function destroy($id)
{
    // Find the user by ID
    $user = User::find($id);

    // If user not found, return a 404 response
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Delete the user
    $user->delete();

    // Return a 204 response indicating the deletion was successful
    return response()->json(null, 204);
}
  public function getRole(Request $request)
    {
        // Validate the token
        $token = $request->bearerToken();

        if (!$token || !Auth::guard('api')->setToken($token)->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $request->validate([
            'username' => 'required|string|max:255'
        ]);

        $username = $request->input('username');

        // Find the user by username
        $user = User::where('username', $username)->first();

        // If user not found, return a 404 response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Return the role of the user
        return response()->json(['role' => $user->role]);
    }
}
