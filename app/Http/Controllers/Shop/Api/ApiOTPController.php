<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class ApiOTPController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = $validated['phone'];

        $key = 'otp-send:' . $phone;

        // Prevent OTP spam
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many OTP requests. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        // Generate 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        // Find or create customer
        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => 'Customer',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // Store hashed OTP
        $user->otp_code = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->otp_attempts = 0;
        $user->save();

        /*
         * TODO:
         * Send $otp through your SMS provider.
         *
         * Example:
         * SmsService::send($phone, "Your OTP is {$otp}");
         */

        return response()->json([
            'message' => 'OTP sent successfully.',
            'expires_in' => 300,
        ]);
    }

    /**
     * Verify OTP and login customer.
     */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid OTP.',
            ], 422);
        }

        // Maximum verification attempts
        if ($user->otp_attempts >= 5) {
            return response()->json([
                'message' => 'Too many incorrect attempts. Please request a new OTP.',
            ], 429);
        }

        // Check expiration
        if (
            !$user->otp_expires_at ||
            now()->greaterThan($user->otp_expires_at)
        ) {
            return response()->json([
                'message' => 'OTP has expired. Please request a new OTP.',
            ], 422);
        }

        // Verify OTP
        if (
            !$user->otp_code ||
            !Hash::check($validated['otp'], $user->otp_code)
        ) {
            $user->increment('otp_attempts');

            return response()->json([
                'message' => 'Invalid OTP.',
            ], 422);
        }

        // OTP successfully verified
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'phone_verified_at' => now(),
        ]);

        // Create API token if using Laravel Sanctum
        $token = $user->createToken('ecommerce')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout customer.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
