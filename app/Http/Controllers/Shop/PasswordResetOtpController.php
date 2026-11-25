<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Crypt;
use Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class PasswordResetOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if existing OTP is still valid
        if (
            $user->otp &&
            $user->otp_expires_at &&
            now()->lt($user->otp_expires_at)
        ) {
            // Existing OTP is still valid
            $otp = Crypt::decryptString($user->otp);

            // Send the same OTP again
            $user->notify(new SendOtpNotification($otp));

            return response()->json([
                'success' => true,
                'otp' => $otp,
                'message' => 'Your existing OTP is still valid and has been sent again.',
            ]);
        }

        // Existing OTP expired or does not exist
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            // Store encrypted OTP so it can be retrieved again
            'otp' => Crypt::encryptString($otp),

            // OTP valid for 15 minutes
            'otp_expires_at' => now()->addMinutes(15),
        ])->save();

        $user->notify(new SendOtpNotification($otp));

        return response()->json([
            'success' => true,
            'otp' => $otp,
            'message' => 'A new 6-digit OTP has been sent to your email address.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check OTP existence and expiration
        if (
            !$user->otp ||
            !$user->otp_expires_at ||
            $user->otp_expires_at->isPast()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
            ], 422);
        }

        try {
            // Decrypt the stored OTP
            $storedOtp = Crypt::decryptString($user->otp);

            // Compare submitted OTP with stored OTP
            if ($request->otp !== $storedOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP code.',
                ], 422);
            }
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. Proceed to reset password.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check OTP existence and expiration
        if (
            !$user->otp ||
            !$user->otp_expires_at ||
            $user->otp_expires_at->isPast()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
            ], 422);
        }

        try {
            // Decrypt stored OTP
            $storedOtp = Crypt::decryptString($user->otp);

            // Compare submitted OTP
            if ($request->otp !== $storedOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP code.',
                ], 422);
            }
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP code.',
            ], 422);
        }

        // Reset password and invalidate OTP
        $user->forceFill([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful. You can now login with your new password.',
        ]);
    }
}
