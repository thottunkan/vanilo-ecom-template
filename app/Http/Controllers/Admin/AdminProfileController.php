<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit($id)
    {
        $admin = User::find($id);
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            // 'email' => 'nullable|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'language' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'password' => 'nullable|confirmed|min:8',
        ]);

        // if (!empty($validated['password'])) {
        //     $validated['password'] = Hash::make($validated['password']);
        // } else {
        //     unset($validated['password']);
        // }

        $admin = User::find($id);
        $admin->update($validated);

        return redirect()
            ->route('admin.profile.edit', $id)
            ->with('success', 'updated successfully.');
    }

    public function page_chagePassword($id){
        $admin = User::find($id);
        return view('admin.profile.change-password',compact('admin'));
    }

    public function changePassword(Request $request, $id)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::findOrFail($id);

        // Check current password
        if (!Hash::check($validated['current_password'], $admin->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.profile.edit', $id)
            ->with('success', 'Password changed successfully.');
    }
}
