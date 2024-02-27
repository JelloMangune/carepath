<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
            ->with('barangay') // Eager load the 'barangay' relationship
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        $response = [
            'token' => $token,
            'user_type' => $user->user_type,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email, // Add the user's email
            'barangay_id' => null,
            'barangay_name' => null,
            'barangay_location' => null,
        ];

        if ($user->barangay) {
            $response['barangay_id'] = $user->barangay->id; // Provide the barangay ID
            $response['barangay_name'] = $user->barangay->name; // Provide the barangay name
            $response['barangay_location'] = $user->barangay->location; // Provide the barangay location
        }

        return response()->json($response, 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users', // Add email validation
            'password' => 'required|min:6|confirmed',
            'user_type' => 'required|integer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password_reset_token' => null,
            'email' => $request->email, // Assign the email
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type, // Assign the user_type
            'barangay_id' => $request->barangay_id, // Assign the barangay
        ]);

        $token = $user->createToken('token-name')->plainTextToken;
        $response = ['data' => ['token' => $token]];

        return response()->json($response, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
    
}
