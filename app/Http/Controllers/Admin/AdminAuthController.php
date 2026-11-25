<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login()
    {
        return view('admin.login.index');
    }

    public function authenticate(Request $request)
    {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:12',
        ]);

        if (
            auth()->attempt([
                'email' => $data['email'],
                'password' => $data['password'],
            ])
        ) {

            $user = auth()->user();

            if ($user->role !== 'admin') {
                auth()->logout();
                return back()->with('error', 'You are not authorized to access the admin panel.');
            }

            $request->session()->regenerate();

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Welcome to admin dashboard.');
        }

        return back()
            ->withInput()
            ->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }

    public function changePassword(Request $request){
        
    }
}
