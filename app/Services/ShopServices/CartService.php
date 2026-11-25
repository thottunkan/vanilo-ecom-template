<?php

namespace App\Services\ShopServices;

use App\Utils\ProductUtility;
use Exception;
use Illuminate\Http\Request;
use Log;
use Vanilo\Cart\Facades\Cart;

class CartService
{
    public function addToCart(Request $request): array
    {
        try {

            $data = $request->validate([
                'product_id' => 'required|exists:master_products,id',
                'variant_id' => 'nullable|integer|exists:master_product_variants,id',
                'quantity' => 'required|integer|min:1',
                'configuration' => 'nullable|array',
                'configuration.color' => 'nullable|string',
                'configuration.size' => 'nullable|string',
            ]);

            $buyable = ProductUtility::has_product_varients(
                $data['product_id'],
                $data['variant_id']
            );

            if (!$buyable) {
                return [
                    'status' => false,
                    'message' => 'Product not found.',
                ];
            }

            Cart::addItem($buyable, $data['quantity'], $data['configuration'] ?? []);

            return [
                'success' => true,
                'message' => 'Product added to cart successfully.',
            ];

        } catch (Exception $th) {
            Log::info($th->getMessage());
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function getCart(): array
    {
        try {
            return [
                'status' => true,
                'data' => Cart::model(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function exists(): array
    {
        try {
            return [
                'status' => true,
                'exists' => Cart::itemCount() > 0,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'exists' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function count(): array
    {
        try {
            return [
                'status' => true,
                'count' => Cart::itemCount(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'count' => 0,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function remove($itemId): array
    {
        try {
            $userId = auth()->id();

            if (!$userId) {
                return [
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ];
            }

            // Get authenticated user's cart
            $cart =Cart::where('user_id', auth()->id())->first();

            if (!$cart) {
                return [
                    'status' => false,
                    'message' => 'Cart not found.',
                ];
            }

            // Get item only from this user's cart
            $item = $cart->items()
                ->where('id', $itemId)
                ->first();

            if (!$item) {
                return [
                    'status' => false,
                    'message' => 'Cart item not found.',
                ];
            }

            // Vanilo method from your Cart model
            $cart->removeItem($item);

            // Refresh cart items
            $cart->load('items');

            return [
                'status' => true,
                'message' => 'Item removed successfully.',
                'data' => $cart,
            ];

        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Failed to remove cart item.',
                'error' => $th->getMessage(),
            ];
        }
    }

    public function clear(): array
    {
        try {
            Cart::destroy();

            return [
                'status' => true,
                'message' => 'Cart cleared successfully.',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    public function updateCart(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Cart::getItemById($itemId);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        Cart::updateItem($itemId, ['quantity' => $request->input('quantity')]);

        return response()->json([
            'status' => true,
            'message' => 'Cart updated.',
            'cart_total' => Cart::total(),
        ]);
    }
}