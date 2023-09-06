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
    
        $user = User::where('username', $request->username)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $token = $user->createToken('token-name')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user_type' => $user->user_type,
            'username' => $user->username,
            'name' => $user->name,
            'id' => $user->id,
        ], 200);
    }
    

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users', // Add email validation
            'password' => 'required|min:6|confirmed',
            'user_type' => 'required|integer', // Add user_type validation
            'barangay_id' => 'required|integer', // Add barangay validation
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
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
