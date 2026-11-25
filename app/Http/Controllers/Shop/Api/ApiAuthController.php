<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;

use App\Services\ShopServices\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    use ApiResponseTrait;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(Request $request)
    {
        
        return $this->userService->create($request);
    }

    public function login(Request $request)
    {
        return $this->userService->authenticate($request);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user()
    {
        return $this->userService->getUser();
    }

    public function changepassword(Request $request)
    {
        $data = $this->userService->changepassword($request);
        return response()->json($data, 200);
    }
}
