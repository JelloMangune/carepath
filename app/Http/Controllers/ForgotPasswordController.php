<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmailResetPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Send a password reset email to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Generate a token and save it to the user
        $user->password_reset_token = Str::random(60);
        $user->save();

        // Send an email to the user with a link containing the token for password reset
        Mail::to($user)->send(new EmailResetPassword($user->password_reset_token));

        return response()->json(['data' => 'Password reset email sent'], 200);
    }

    /**
     * Update the user's password based on the reset token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_reset_token' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('password_reset_token', $request->password_reset_token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null; // Invalidate the token after the password change
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
}
