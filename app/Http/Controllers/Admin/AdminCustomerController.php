<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Konekt\Customer\Models\Customer;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->get();
        return view('admin.customer.index',compact('customers'));
    }

    public function update(Request $request, $id)
    {

    }

    public function block_user(){
        
    }
}
