<?php

namespace App\Services\AdminServices;

use Illuminate\Http\Request;
class AddressService
{

    public function store_address(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'pincode' => 'required|integer',
            'address' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'type' => 'required|string',
            'is_default' => 'required|boolean',
        ]);
    }

    public function update_address(Request $request, $id)
    {

    }

    public function delete_address($id)
    {

    }


}