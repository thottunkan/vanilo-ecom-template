<?php

namespace App\Services\ShopServices;


use Illuminate\Support\Facades\Auth;
use Vanilo\Foundation\Models\Address;

class AddressService
{
    /**
     * Create a new address.
     */
    public function create(array $data): Address
    {
        $data['user_id'] = Auth::id();
        $data['name'] = Auth::user()->name;
        return Address::create($data);
    }


    /**
     * Update an address.
     */
    public function update(int $id, array $data): Address
    {
        $address = $this->findUserAddress($id);

        $address->update($data);

        return $address->fresh();
    }


    /**
     * Delete an address.
     */
    public function delete(int $id): bool
    {
        $address = $this->findUserAddress($id);

        return $address->delete();
    }


    /**
     * Find address belonging to current user.
     */
    public function findUserAddress(int $id)
    {
        return Address::where('user_id', $id)->get();
    }

    public function getUserAddresses()
    {
        return Address::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function find(int $id): Address
    {
        return Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}