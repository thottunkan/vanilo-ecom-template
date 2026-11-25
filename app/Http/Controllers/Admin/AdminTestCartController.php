<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Services\ShopServices\CartService;
use Illuminate\Http\Request;
use Log;
use Vanilo\Cart\Facades\Cart;

class AdminTestCartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $data = $this->cartService->addToCart($request);
        //Log::info($data);
        return response()->json($data);
    }

    /**
     * Show cart
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Cart::model()
        ]);
    }

    /**
     * Check if cart has items
     */
    public function exists()
    {
        return response()->json([
            'exists' => Cart::itemCount() > 0
        ]);
    }

    /**
     * Get total item count
     */
    public function count()
    {
        return response()->json([
            'count' => Cart::itemCount()
        ]);
    }

    /**
     * Remove an item from cart
     */
    public function removeFromCart($itemId)
    {
        $item = Cart::getItem($itemId);

        if ($item) {
            Cart::removeItem($item);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed',
            'data' => Cart::model()
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Cart::destroy();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
}