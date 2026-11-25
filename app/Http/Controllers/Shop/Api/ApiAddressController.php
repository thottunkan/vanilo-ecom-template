<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;

use App\Services\ShopServices\AddressService;
use Auth;
use Illuminate\Http\Request;

class ApiAddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function getCustomerAddress()
    {
        try {
            $user_id = auth()->id();
            $address = $this->addressService->findUserAddress($user_id);
            return response()->json([
                'success' => true,
                'message' => 'Address retrieved successfully.',
                'data' => $address,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store address.
     */
    public function add(Request $request)
    {
        try {
           // return $request;
            $data = $this->validateAddress($request);

            $address = $this->addressService->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully.',
                'data' => $address,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update address.
     */
    public function update(Request $request, $id)
    {
        try {

            $data = $this->validateAddress($request);

            $address = $this->addressService->update($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully.',
                'data' => $address,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Delete address.
     */
    public function delete($id)
    {
        try {

            $this->addressService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully.',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Address validation rules.
     */
    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'type' => 'nullable|string|max:50',
            'address_type' => 'nullable|string|max:200',

            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',

            'postalcode' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',

            'address' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',

            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',

            'district' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',

            'street' => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:50',
            'apartment' => 'nullable|string|max:50',

            'landmark' => 'nullable|string|max:255',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'is_default' => 'nullable|boolean',
            'is_billing' => 'nullable|boolean',
            'is_shipping' => 'nullable|boolean',

            'notes' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);
    }
}