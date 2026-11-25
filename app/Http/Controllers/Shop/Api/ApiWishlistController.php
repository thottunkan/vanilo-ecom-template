<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Auth;
use Illuminate\Http\Request;

class ApiWishlistController extends Controller
{

    public function mywishlist()
    {
        try {
            $user = Auth::user();

            $wishlist = Wishlist::with(['product','product.media','product.taxons'])
                ->where('user_id', $user->id)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Wishlist fetched successfully.',
                'data' => $wishlist
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllWishlistProducts(Request $request)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->with('product')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Wishlist fetched successfully.',
            'data' => $wishlist
        ]);
    }

    public function addToWishlist(Request $request)
    {
        try {
            // Check if user is authenticated
            // if (!Auth::check()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Login first',
            //         'data' => []
            //     ], 401);
            // }

            $data = $request->validate([
                'product_id' => 'required|integer',
            ]);

            // Get authenticated user's ID
            $userId = Auth::id();

            $masterProductId = $data['product_id'];

            // Check if item already exists in wishlist
            $alreadyExists = Wishlist::where('user_id', $userId)
                ->where('master_product_id', $masterProductId)
                ->exists();

            if ($alreadyExists) {
                return response()->json([
                    'status' => false,
                    'icon' => '❤️',
                    'message' => 'Item already in wishlist',
                    'data' => []
                ], 200);
            }

            // Prepare data for wishlist
            $data['user_id'] = $userId;
            $data['master_product_id'] = $masterProductId;
            unset($data['product_id']);

            $wishlist = Wishlist::create($data);

            return response()->json([
                'status' => true,
                'icon' => '❤️',
                'message' => "Added to wishlist",
                'data' => $wishlist
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add' . $e->getMessage(),
                'data' => []
            ], 200);
        }
    }


    public function removeFromWishlist($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Login first',
                'data' => []
            ], 401);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('master_product_id', $productId)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.',
                'data' => []
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,

            'message' => 'Item removed from wishlist.',
            'data' => []
        ], 200);
    }

    public function count()
    {
        try {
            $count = Wishlist::itemCount();

            return response()->json([
                'success' => true,
                'count' => (int) $count,
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('Wishlist count error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'count' => 0,
                'message' => 'Unable to get wishlist count.',
            ], 500);
        }
    }

    public function clearWishlist()
    {
        try {
            if (Wishlist::count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wishlist is empty.'
                ]);
            }

            Wishlist::query()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared..!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear wishlist.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
