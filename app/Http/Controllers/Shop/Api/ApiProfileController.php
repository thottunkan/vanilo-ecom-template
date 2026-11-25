<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ShopServices\ProfileService;
use App\Services\ShopServices\UserService;
use Auth;
use Illuminate\Http\Request;
use Konekt\Customer\Models\Customer;

class ApiProfileController extends Controller
{
    protected ProfileService $profileService;
    protected UserService $userService;

    public function __construct(ProfileService $profileService, UserService $userService)
    {
        $this->profileService = $profileService;
        $this->userService = $userService;
    }

    public function updateProfile(Request $request, $id = null)
    {
        try {
            $user = Auth::user();
            $user->update($request->all());
            return response()->json(["status" => true, "message" => "Profile updated successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(["status"=> false,"message"=> $e->getMessage()],500);
        }

    }

    public function getProfile()
    {
        // return $this->profileService->getProfile($id);
        $user = User::with([])
            ->where('id', auth()->id())
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Profile details fetched successfully!',
            'data' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
     
        return $this->userService->changePassword($request);
    }


}