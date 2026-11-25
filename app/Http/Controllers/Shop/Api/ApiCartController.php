<?php
namespace App\Http\Controllers\Shop\Api;
use App\Http\Controllers\Controller;
use App\Services\ShopServices\CartService;
use App\Utils\ProductUtility;
use DB;
use Exception;
use Illuminate\Http\Request;
//use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartItem;
use Vanilo\Taxes\Models\TaxRate;


class ApiCartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        $data = $this->cartService->addToCart($request);
        //Log::info($data);
        return response()->json($data);
    }

    /**
     * Add product to cart
     */
    // public function add(Request $request)
    // {
    //     $result = $this->cartService->addToCart($request);

    //     return response()->json(
    //         $result,
    //         $result['success'] ? 200 : 422
    //     );
    // }
    // public function add(Request $request)
    // {

    //     try {
    //         $validated = $request->validate([
    //             'master_product_id' => 'required|integer',
    //             'master_product_variant_id' => 'nullable|integer',
    //             'quantity' => 'required|integer|min:1',
    //             'configuration' => 'nullable|array',
    //             'configuration.color' => 'nullable|string',
    //             'configuration.size' => 'nullable|string',
    //         ]);

    //         $buyable = ProductUtility::has_product_varients(
    //             $validated['master_product_id'],
    //             $validated['master_product_variant_id'] ?? null
    //         );

    //         if (!$buyable) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Product not found.',
    //             ], 404);
    //         }

    //         $taxdetais = TaxRate::findOrFail($buyable->tax_rate_id);

    //         $price = (float) $buyable->price;
    //         $quantity = (int) $validated['quantity'];
    //         $taxRate = (float) ($taxdetais->rate ?? 0);

    //         $itemSubtotal = $price * $quantity;
    //         $taxAmount = round($itemSubtotal * ($taxRate / 100), 2);

    //         $cart = Cart::addItem(
    //             $buyable,
    //             $quantity,
    //             [
    //                 'attributes' => [
    //                     'configuration' => [
    //                         'percentage' => $taxdetais->name,
    //                         'taxrate' => $taxRate,
    //                         'tax' => $taxAmount,
    //                     ],
    //                 ],
    //             ]
    //         );

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Item added to cart.',
    //             'data' => $cart,
    //         ], 201);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //             'data' => null,
    //         ], 500);
    //     }
    // }



    public function getcartItems()
    {
        try {
            $user = auth()->user();

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is empty.',
                    'data' => []
                ], 200);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)->get();

            return response()->json([
                'success' => true,
                'message' => 'Cart items retrieved successfully.',
                'cart_id' => $cart->id,
                'count' => $cartItems->count(),
                'data' => $cartItems
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve cart items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function add(Request $request)
    {
        try {

            $validated = $request->validate([
                'master_product_id' => 'required|integer',
                'master_product_variant_id' => 'nullable|integer',
                'quantity' => 'required|integer|min:1',

                'configuration' => 'nullable|array',
                'configuration.color' => 'nullable|string',
                'configuration.size' => 'nullable|string',
            ]);

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | Get product / variant
            |--------------------------------------------------------------------------
            */

            $buyable = ProductUtility::has_product_varients(
                $validated['master_product_id'],
                $validated['master_product_variant_id'] ?? null
            );

            if (!$buyable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            $taxDetails = TaxRate::find($buyable->tax_rate_id);

            $price = (float) $buyable->price;
            $quantity = (int) $validated['quantity'];

            $taxRate = (float) ($taxDetails->rate ?? 0);

            $itemSubtotal = $price * $quantity;

            $taxAmount = round(
                $itemSubtotal * ($taxRate / 100),
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Find existing cart or create new cart
            |--------------------------------------------------------------------------
            */

            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Configuration
            |--------------------------------------------------------------------------
            */

            $configuration = [
                // 'attributes' => [
                // 'configuration' => [
                'color' => $validated['configuration']['color'] ?? null,
                'size' => $validated['configuration']['size'] ?? null,

                'percentage' => $taxDetails->name ?? null,
                'taxrate' => $taxRate,
                'tax' => $taxAmount,
                //  ],
                // ],
            ];

            /*
            |--------------------------------------------------------------------------
            | Check existing cart item
            |--------------------------------------------------------------------------
            */

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_type', 'master_product')
                ->where('product_id', $buyable->id)
                ->first();

            if ($cartItem) {

                /*
                 * Existing product:
                 * increase quantity instead of creating another item
                 */

                $cartItem->quantity += $quantity;

                $newSubtotal = $price * $cartItem->quantity;

                // $newTaxAmount = round(
                //     $newSubtotal * ($taxRate / 100),
                //     2
                // );
                $newTaxAmount = $cartItem->configuration['tax'] * $cartItem->quantity;

                // $configuration['attributes']['configuration']['tax']
                //     = $newTaxAmount;
                $configuration['tax'] = $newTaxAmount;

                $cartItem->price = $price;
                $cartItem->configuration = $configuration;

                $cartItem->save();

            } else {

                /*
                 * New product:
                 * use the EXISTING cart_id
                 */

                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_type' => 'master_product',
                    'product_id' => $buyable->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'configuration' => $configuration,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Return cart
            |--------------------------------------------------------------------------
            */

            $cart->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart.',
                'data' => [
                    'cart' => $cart,
                    // 'cart_item' => $cartItem,
                ],
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
    /**
     * Show cart
     */
    public function index()
    {
        $result = $this->cartService->getCart();
        return response()->json(
            $result,
            $result['success'] ? 200 : 500
        );
    }

    /**
     * Check if cart has items
     */
    public function exists()
    {
        $result = $this->cartService->exists();
        return response()->json(
            $result,
            $result['success'] ? 200 : 500
        );
    }

    /**
     * Get total item count
     */
    public function count()
    {
        $result = $this->cartService->count();
        return response()->json(
            $result,
            $result['success'] ? 200 : 500
        );
    }

    /**
     * Remove item
     */
    public function remove($itemId)
    {
        try {
            // Associate current Vanilo cart with authenticated user
            $user = auth()->user();
            // Get current user's cart
            $cart = \Vanilo\Cart\Models\Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart not found.',
                ], 404);
            }

            // Find item in this user's cart
            $item = $cart->items()
                ->where('id', $itemId)
                ->first();

            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found.',
                ], 404);
            }

            // Remove item
            $item->delete();

            return response()->json([
                'status' => true,
                'message' => 'Item removed successfully.',
                'data' => Cart::model(),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove cart item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clearCartItems()
    {
        try {
            $user = auth()->user();
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Find user's cart
            |--------------------------------------------------------------------------
            */
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is already empty.',
                    'data' => null,
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Delete all cart items belonging to this cart
            |--------------------------------------------------------------------------
            */
            CartItem::where('cart_id', $cart->id)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully.',
                'data' => [
                    'cart_id' => $cart->id,
                    'items' => [],
                ],
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function deleteCart(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data' => null,
                ], 401);
            }
            DB::beginTransaction();
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found.',
                    'data' => null,
                ], 404);
            }
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cart deleted successfully.',
                'data' => null,
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cart.',
                'error' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function updateCart(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $item = Cart::getItemById($itemId);
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }
        $quantity = (int) $validated['quantity'];
        // Get product and variant from cart item
        $masterProductId = $item->product_id;
        $masterProductVariantId = $item->variant_id ?? null;
        // Get the same buyable product used in add()
        $buyable = ProductUtility::has_product_varients(
            $masterProductId,
            $masterProductVariantId
        );
        if (!$buyable) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }
        // Get tax details exactly like add()
        $taxdetais = TaxRate::findOrFail($buyable->tax_rate_id);
        $price = (float) $buyable->price;
        $taxRate = (float) ($taxdetais->rate ?? 0);
        // Recalculate tax based on updated quantity
        $itemSubtotal = $price * $quantity;
        $taxAmount = round($itemSubtotal * ($taxRate / 100), 2);
        // Update cart item
        Cart::updateItem($itemId, [
            'quantity' => $quantity,
            'attributes' => [
                'configuration' => [
                    'percentage' => $taxdetais->name,
                    'taxrate' => $taxRate,
                    'tax' => $taxAmount,
                ],
            ],
        ]);
        // Get updated cart item
        $cart = Cart::getItemById($itemId);
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'data' => $cart,
        ], 200);
    }

}