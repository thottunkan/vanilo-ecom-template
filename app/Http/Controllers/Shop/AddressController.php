<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected AddressService $addressService;


    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }


    /**
     * Display all user addresses.
     */
    public function index()
    {
        $addresses = $this->addressService->getUserAddresses();

        return view('shop.addresses.index', compact('addresses'));
    }


    /**
     * Show create address form.
     */
    public function create()
    {
        return view('shop.addresses.create');
    }


    /**
     * Store new address.
     */
    public function store(Request $request)
    {
        $data = $this->validateAddress($request);

        $this->addressService->create($data);

        return redirect()
            ->route('shop.addresses.index')
            ->with('success', 'Address created successfully.');
    }


    /**
     * Show edit address form.
     */
    public function edit($id)
    {
        $address = $this->addressService->find($id);

        return view('shop.addresses.edit', compact('address'));
    }


    /**
     * Update address.
     */
    public function update(Request $request, $id)
    {
        $data = $this->validateAddress($request);

        $this->addressService->update($id, $data);

        return redirect()
            ->route('shop.addresses.index')
            ->with('success', 'Address updated successfully.');
    }


    /**
     * Delete address.
     */
    public function destroy($id)
    {
        $this->addressService->delete($id);

        return redirect()
            ->route('shop.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    /**
     * Validation rules.
     */
    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'type' => 'nullable|string|max:50',

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