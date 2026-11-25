<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    
    public function index()
    {
        return view('admin.contactmessage.index');
    }

    public function create()
    {
        return view('admin.contactmessage.create');
    }

    public function edit($id)
    {
        return view('admin.contactmessage.edit');
    }

    public function delete($id)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
