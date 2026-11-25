<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    //

    public function index()
    {
        $items = Wishlist::where('user_id', Auth::id())->with(['product.media', 'product.taxons', 'product.discount'])->get();
     
        return view('shop.wishlist.index', compact('items'));
    }

    public function addToWishlist(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.',
            ], 401);
        }

        try {
            $data = $request->validate([
                'master_product_id' => 'required|integer',
            ]);

            $wishlist = Wishlist::firstOrCreate([
                'master_product_id' => $data['master_product_id'],
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist.',
                'data' => $wishlist,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update wishlist.',
            ], 500);
        }
    }

    public function delete($id)
    {
        Wishlist::where('user_id', Auth::id())->where('master_product_id', $id)->delete();
        return back()->with('success', 'Removed from wishlist.');
    }
}
