<?php

namespace App\Services\ShopServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Konekt\Customer\Models\Customer;

class ProfileService
{
    public function updateProfile(Request $request, $id = null)
    {
        try {


            $userId = Auth::id();
            $customer = Customer::find($userId);
            // If ID is provided, find that customer's profile
            // belonging to the authenticated user.
            if ($id) {
                $customer = Customer::where('id', $id)
                    ->where('user_id', $userId)
                    ->first();

                if (!$customer) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer not found.',
                        'data' => null,
                    ], 404);
                }
            } else {
                // If no ID is provided, find the authenticated
                // user's existing customer profile.
                $customer = Customer::where('user_id', $userId)->first();
            }

            // Validate request
            $data = $this->validateCustomer(
                $request->all(),
                $customer?->id
            );

            // Always associate the profile with authenticated user
            $data['user_id'] = $userId;
            $data['type'] = $data['type'] ?? 'customer';

            if ($customer) {
                $customer->update($data);
                $message = 'Profile updated successfully.';
            } else {
                $customer = Customer::create($data);
                $message = 'Profile created successfully.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $customer->fresh(),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'data' => null,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'customer_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the profile.',
                'data' => null,
            ], 500);
        }
    }


    public function getProfile($id = null)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data' => null,
                ], 401);
            }

            // Get customer by provided user ID or authenticated user's ID
            $userId = $id ?? Auth::id();

            $customer = Customer::where('user_id', $userId)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found.',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully.',
                'data' => $customer,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Get profile failed', [
                'user_id' => Auth::id(),
                'requested_user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'data' => null,
            ], 500);
        }
    }


    private function validateCustomer(array $data, $customerId = null)
    {
        return Validator::make($data, [
            'type' => 'nullable|in:individual,organization',

            'email' => 'nullable|email|max:255|unique:customers,email,' . $customerId,
            'phone' => 'nullable|string|max:22',

            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',

            'company_name' => 'nullable|string|max:255',
            'tax_nr' => 'nullable|string|max:17',
            'registration_nr' => 'nullable|string|max:255',

            'currency' => 'nullable|string|size:3',
            'timezone' => 'nullable|string|max:255',

            'customer_number' => 'nullable|string|max:255',
            'acquired_via' => 'nullable|string|max:255',
            'acquisition_details' => 'nullable|array',

            'country_code' => 'nullable|string|max:255',
            'state_code' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

            'street' => 'nullable|string|max:255',
            'street_number' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'suite' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',

            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            'delivery_instructions' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'is_billing' => 'nullable|boolean',
            'is_shipping' => 'nullable|boolean',

            'vat_number' => 'nullable|string|max:255',
            'company_registration_number' => 'nullable|string|max:255',

            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:255',

            'door_code' => 'nullable|string|max:255',
            'entrance' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'plot_number' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:255',

            'formatted_address' => 'nullable|string|max:255',

            'metadata' => 'nullable|array',
        ])->validate();
    }
}