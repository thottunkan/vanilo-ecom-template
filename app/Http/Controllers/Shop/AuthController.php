<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use App\Services\ShopServices\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Vanilo\Foundation\Models\Customer;

class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLogin()
    {
        return view('shop.auth.login');
    }
    public function showRegister()
    {
        return view('shop.auth.register');
    }

    public function showChangePassword()
    {
        return view('shop.account.changepassword');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->only('email'));
        }
        $request->session()->regenerate();
        return redirect()->intended(route('shop.home'))->with('success', 'Welcome back.');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'customer',
                'is_active' => 'active',
            ]);
            Customer::firstOrCreate(['user_id' => $user->id], ['email' => $user->email]);
        });

        Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
        $request->session()->regenerate();
        return redirect()->route('shop.home')->with('success', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        $user_id = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('shop.home')->with('success', 'You have been logged out.');
    }

    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Signed out successfully']);
    }

    public function changePassword(Request $request)
    {
        $data = $this->userService->changePassword($request);

        return redirect()
            ->route('shop.showchange-password')
            ->with([
                'status' => $data['status'],
                'message' => $data['message'],
            ]);
    }

}
