<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Hash;
use Illuminate\Http\Request;

class ApiPasswordResetOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate a secure 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        // Send the 6-digit OTP to email
        $user->notify(new SendOtpNotification($otp));

        return response()->json([
            'success' => true,
            'message' => 'A 6-digit OTP has been sent to your email address.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user->otp ||
            !$user->otp_expires_at ||
            $user->otp_expires_at->isPast() ||
            !Hash::check($request->otp, $user->otp)
        ) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
        }

        return response()->json(['message' => 'OTP verified successfully. Proceed to reset password.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Validate OTP prior to password rewrite
        if (
            !$user->otp ||
            !$user->otp_expires_at ||
            $user->otp_expires_at->isPast() ||
            !Hash::check($request->otp, $user->otp)
        ) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
        }

        // Reset password & invalidate used OTP
        $user->forceFill([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        return response()->json(['message' => 'Password reset successful. You can now login with your new password.']);
    }

}
