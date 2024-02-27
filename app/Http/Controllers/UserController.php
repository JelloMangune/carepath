<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users (admin only).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get all users with associated barangay information if barangay_id is not null
        $users = User::where('username', '!=', 'admin')
        ->with(['barangay:id,name']) // Conditionally eager load for users with a non-null barangay_id
        ->orderBy('barangay_id')
        ->get();


        return response()->json(['data' => $users], 200);
    }

    /**
     * Get a single user by ID (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the user by ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    /**
     * Update a user by ID (admin only or the user themselves).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if the authenticated user is found
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Update the user's attributes
        $userData = $request->all();

        // Check if the request contains a password field and hash it
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        return response()->json(['data' => $user], 200);
    }

    public function updateByAdmin(Request $request, $id)
    {
        // Find the user by the provided $id
        $user = User::find($id);

        // Check if the user is found
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Update the user's attributes
        $userData = $request->all();

        // Check if the request contains a password field and hash it
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        return response()->json(['data' => $user], 200);
    }

    /**
     * Delete a user by ID (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if the authenticated user has admin privileges (user_type = 0)
        if (Auth::user()->user_type !== 0) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the user by ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }
}
