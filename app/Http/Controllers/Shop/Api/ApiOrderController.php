<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStatusHistory;
use App\Services\ShopServices\OrderService;
use App\Utils\ProductUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Vanilo\Cart\Models\Cart as VaniloCart;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\Order\Contracts\OrderFactory;
use Vanilo\Order\Models\Order;

class ApiOrderController extends Controller
{
    protected OrderFactory $orderFactory;
    protected OrderService $orderService;

    public function __construct(
        OrderFactory $orderFactory,
        OrderService $orderService
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderService = $orderService;
    }

    /**
     * Get authenticated user's orders
     */
    public function index(Request $request)
    {
        return $this->orderService->orders();
    }

    /**
     * Place order directly from request
     */
    public function placeOrderFromReq(Request $request)
    {
        return $this->orderService->placeOrder($request);
    }

    /**
     * Place order from authenticated user's active cart
     */
    public function placeOrderFromCart(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get user's active cart directly from database
        |--------------------------------------------------------------------------
        |
        | Do not use Cart::isEmpty() here because this is an API application
        | and the session cart may not be the same cart belonging to the user.
        |
        */

        $cart = VaniloCart::query()
            ->where('user_id', $user->id)
            ->where('state', 'active')
            ->with('items')
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check cart items
        |--------------------------------------------------------------------------
        */

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Build order items
        |--------------------------------------------------------------------------
        */

        try {
            $orderItems = $this->buildOrderItemsFromCart($cart);

            if (empty($orderItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create order items from cart.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate order totals
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;
            $taxTotal = 0;
            $grandTotal = 0;

            foreach ($orderItems as $item) {
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];

                $lineSubtotal = $price * $quantity;

                $tax = (float) (
                    $item['configuration']['tax'] ?? 0
                );

                $subtotal += $lineSubtotal;
                $taxTotal += $tax;
            }

            $grandTotal = $subtotal + $taxTotal;

            /*
            |--------------------------------------------------------------------------
            | Create order
            |--------------------------------------------------------------------------
            */

            $order = DB::transaction(function () use ($user, $validated, $orderItems, $cart) {
                $order = $this->orderFactory->createFromDataArray(
                    [
                        'user_id' => $user->id,

                        'shipping_address' =>
                            $validated['shipping_address'] ?? null,

                        'billing_address' =>
                            $validated['billing_address'] ?? null,
                    ],
                    $orderItems
                );

                /*
                |--------------------------------------------------------------------------
                | Clear cart items
                |--------------------------------------------------------------------------
                |
                | Keep the cart record but remove its items.
                |
                */

                $cart->items()->delete();

                return $order;
            });

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',

                'data' => [
                    'order' => $order,

                    'summary' => [
                        'subtotal' => round($subtotal, 2),
                        'tax' => round($taxTotal, 2),
                        'grand_total' => round($grandTotal, 2),
                    ],
                ],
            ], 201);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build Vanilo order items from user's DB cart.
     */
    private function buildOrderItemsFromCart(VaniloCart $cart): array
    {
        $orderItems = [];
        foreach ($cart->items as $cartItem) {
            /*
            |--------------------------------------------------------------------------
            | Resolve buyable product
            |--------------------------------------------------------------------------
            */

            $buyable = null;

            /*
            | Vanilo CartItem normally provides getBuyable().
            */
            if (method_exists($cartItem, 'getBuyable')) {
                $buyable = $cartItem->getBuyable();
            }

            /*
            | Fallback to your existing ProductUtility.
            */
            if (!$buyable) {
                $buyable = ProductUtility::has_product_varients(
                    $cartItem->product_id,
                    null
                );
            }



            if (!$buyable) {
                throw new \RuntimeException(
                    "Product {$cartItem->product_id} could not be found."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Determine product type
            |--------------------------------------------------------------------------
            */

            $type = $buyable instanceof MasterProduct
                ? 'master_product'
                : 'master_product_varient';

            /*
            |--------------------------------------------------------------------------
            | Cart configuration
            |--------------------------------------------------------------------------
            */

            $configuration = $cartItem->configuration ?? [];
            if (is_string($configuration)) {
                $configuration = json_decode(
                    $configuration,
                    true
                ) ?? [];
            }

            /*
            |--------------------------------------------------------------------------
            | Quantity and price
            |--------------------------------------------------------------------------
            */

            $quantity = (int) $cartItem->quantity;
            $price = (float) $cartItem->price;

            /*
            |--------------------------------------------------------------------------
            | Tax calculation
            |--------------------------------------------------------------------------
            |
            | taxrate is a percentage.
            |
            | Example:
            |
            | price    = 100
            | quantity = 2
            | taxrate  = 18%
            |
            | subtotal = 200
            | tax      = 200 * 18 / 100 = 36
            |
            */

            $taxRate = (float) (
                $configuration['taxrate'] ?? 0
            );

            $lineSubtotal = $price * $quantity;
            $tax = $lineSubtotal * $taxRate / 100;
            $lineGrandTotal = $lineSubtotal + $tax;

            /*
            |--------------------------------------------------------------------------
            | Order item
            |--------------------------------------------------------------------------
            */
            $orderItems[] = [
                'product' => $buyable,
                'product_type' => $type,
                'product_id' => $buyable->id,
                'quantity' => $quantity,
                'price' => $price,
                'configuration' => array_merge(
                    $configuration,
                    [
                        'taxrate' => $taxRate,
                        'subtotal' => round($lineSubtotal, 2),
                        'tax' => round($tax, 2),
                        'grand_total' => round($lineGrandTotal, 2),
                    ]
                ),
            ];
        }

        return $orderItems;
    }

    public function findOrder($id)
    {
        $order = Order::with('items')->find($id);
        return response()->json($order, 200);
    }
    /**
     * Get delivered orders
     */
    public function getDeliveredOrders()
    {
        $orders = $this->orderService->getOrdersByStatus('delivered');

        return response()->json([
            'status' => true,
            'message' => 'Delivered orders.',
            'orders' => $orders,
        ]);
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders()
    {
        $orders = $this->orderService->getOrdersByStatus('pending');

        return response()->json([
            'status' => true,
            'message' => 'Pending orders.',
            'orders' => $orders,
        ]);
    }

    /**
     * Get cancelled orders
     */
    public function getCancelledOrders()
    {
        $orders = $this->orderService->getOrdersByStatus('cancelled');

        return response()->json([
            'status' => true,
            'message' => 'Cancelled orders.',
            'orders' => $orders,
        ]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        try {

            $request->validate([
                'status' => 'required|string',
                'comment' => 'nullable|string',
            ]);

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            $order->update([
                'status' => $request->status,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                ],
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}