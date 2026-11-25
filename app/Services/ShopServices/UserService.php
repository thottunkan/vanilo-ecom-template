<?php

namespace App\Services\ShopServices;


use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vanilo\Foundation\Models\Customer;

class UserService
{

    use ApiResponseTrait;

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Customer::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $request->phone
            ]);
            // Assign default role
            // $user->assignRole('employee');

            $data['token'] = $user->createToken($request->email)->plainTextToken;
            $data['user'] = $user;
            DB::commit();

            // EmailService::send(
            //     'email-verification',
            //     $user->email,
            //     [
            //         'customer_name' => $user->name,
            //         'site_name' => 'shop demo',
            //         'verification_url' => 'localhost/website',
            //     ]
            // );

            return $this->successResponse($data, 'User is registered successfully', 201);
        } catch (Exception $e) {
            DB::rollBack(); // Undo changes
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->errorResponse('Validation Error!', 422, $e->getMessage());
            //return response()->json(['message' => $e->getMessage()], 201);
        }
    }

    public function authenticate(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|max:12',
            ]);

            // Find user
            $user = User::where('email', $request->email)->first();

            // Check credentials
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse(
                    'error!',
                    401,
                    'Invalid login details'
                );
            }

            $token = $user->createToken(
                $request->email,
                ['*'],
                now()->addMinutes(15)
            )->plainTextToken;
            
            // Prepare response data
            $data['user'] = $user;
            $data['token'] = $token;

            return $this->successResponse(
                $data,
                'Login successful',
                200
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                'Validation error!',
                422,
                $e->errors()
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                'error!',
                500,
                'Something went wrong. Please try again.'
            );
        }
    }


    public function getUser()
    {
        $data = Auth::user();
        return $this->successResponse($data, 'Test Function run successfully', 201);
    }

    public function changePassword(Request $request)
    {

        try {

            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = Auth::user();

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                    'errors' => [
                        'current_password' => [
                            'Current password is incorrect.'
                        ]
                    ]
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            // EmailService::send(
            //     'password-reset',
            //     $user->email,
            //     [
            //         'customer_name' => $user->name,
            //         'website_name' => 'shop demo',
            //         'verification_url' => 'localhost/website',
            //     ]
            // );
            return [
                'success' => true,
                'message' => 'Password updated.',
                'data' => $user
            ];

        } catch (Exception $e) {
            return [
                'success' => true,
                'message' => 'Unable to update password.',
                'data' => []
            ];
        }
    }
}