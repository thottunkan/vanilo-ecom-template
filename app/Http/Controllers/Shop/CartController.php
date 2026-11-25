<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

use App\Services\ShopServices\CartService;
use App\Utils\ProductUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Vanilo\Cart\Facades\Cart;
use Vanilo\Foundation\Models\MasterProduct;


class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public CartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = Cart::getItems();

        $taxtotal = 0;
        $subtotal = 0;
        foreach ($items as $item) {
            $configuration = $item->configuration;

            // Decode JSON if configuration is stored as a string
            if (is_string($configuration)) {
                $configuration = json_decode($configuration, true);
            }
            $subtotal += (float) ($item->price * $item->quantity );
            $taxtotal += (float) ($configuration['tax'] * $item->quantity ?? 0);
        }

        return view('shop.cart.index', [
            'items' => $items,
            'subtotal' => Cart::total(),
            'taxtotal' => $taxtotal,
            'total' => Cart::total() + $taxtotal
        ]);
    }


    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'master_product_id' => 'required|integer',
            'master_product_variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
            'configuration' => 'nullable|array',
            'configuration.color' => 'nullable|string',
            'configuration.size' => 'nullable|string',
        ]);

        $buyable = ProductUtility::has_product_varients(
            $request->input('master_product_id'),
            $request->input('master_product_variant_id')
        );

        if (!$buyable) {
            return back()->with('error', 'Product not found.');
        }

        Cart::addItem(
            $buyable,
            $request->input('quantity', 1),
            [
                'tax_rate_id' => $buyable->tax_rate_id,
                'tax_rate' => (float) ($buyable->taxrate?->rate ?? 0),
            ]
        );

        return back()->with('success', 'Item added to cart.');
    }

    /**
     * Show cart
     */
    // public function index()
    // {
    //     return response()->json([
    //         'success' => true,
    //         'data' => Cart::model()
    //     ]);
    // }

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
     * Remove item
     */
    /**
     * Remove item
     */
    public function remove($itemId)
    {
        // Get the cart item by ID
        $item = Cart::getItems()->firstWhere('id', $itemId);

        if (!$item) {
            return back()->with('error', 'Cart item not found.');
        }

        // Remove the item
        Cart::removeItem($item);

        return back()->with('success', 'Item removed from cart.');
    }


    /**
     * Clear cart
     */
    public function clear()
    {
        Cart::destroy();
        return redirect()->route('shop.cart.index')->with('Cart cleared successfully!.');
    }

    public function update(Request $request, $itemId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the current cart items
        $item = Cart::getItems()->firstWhere('id', $itemId);

        if (!$item) {
            return back()->with('error', 'Cart item not found.');
        }

        // Update the cart item quantity
        $item->quantity = (int) $request->input('quantity');

        $item->save();

        return back()->with('success', 'Cart updated.');
    }

}