<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Utils\ProductUtility;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Konekt\Customer\Models\Customer;
use Throwable;
use Vanilo\Cart\Facades\Cart;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $items = Cart::getItems();

        $taxtotal = 0;
        $subtotal = 0;
        foreach ($items as $item) {
            $configuration = $item->configuration;

            // Decode JSON if configuration is stored as a string
            if (is_string($configuration)) {
                $configuration = json_decode($configuration, true);
            }
            $subtotal += (float) ($item->price * $item->quantity);
            $taxtotal += (float) ($configuration['tax'] * $item->quantity ?? 0);
        }

        return view('shop.checkout.index', [
            'items' => Cart::getItems(),
            'taxtotal' => $taxtotal,
            'subtotal' => Cart::total(),
            'total' => Cart::total() + $taxtotal,
        ]);
    }

    public function store(Request $request, OrderFactory $factory): RedirectResponse
    {
       
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        $orderItems = $this->buildOrderItemsFromCart();

        $grandTotalForOrders = 0;
        foreach ($orderItems as $item) {
            $grandTotalForOrders += $item['confifiguration']['grand_total'] ?? 0;
        }

        try {
            $order = DB::transaction(function () use ($factory, $orderItems, $request) {
                $order = $factory->createFromDataArray([
                    'user_id' => auth()->id(),
                    'shipping_address' => $request->input('shipping_address'),
                    'billing_address' => $request->input('billing_address'),
                ], $orderItems);

                Cart::clear();

                return $order;
            });
            return redirect()
                ->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully..!');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }

    }

    public function success($orderId)
    {
        return view('shop.checkout.success', ['orderId' => $orderId]);
    }

    private function buildOrderItemsFromCart(): array
    {
        $orderItems = [];

        foreach (Cart::getItems() as $cartItem) {

            $buyable = ProductUtility::has_product_varients(
                $cartItem->product_id,
                null
            );
            //$buyable = $cartItem->getBuyable();

            $type = get_class($buyable) === MasterProduct::class
                ? 'master_product'
                : 'master_product_varient';

            $configuration = $cartItem->configuration ?? [];
            if (is_string($configuration)) {
                $configuration = json_decode($configuration, true);
            }
            // $taxtotal = (float) ($configuration['tax'] * $cartItem->quantity ?? 0);
            $tax = (float) ($configuration['tax'] ?? 0);
            $price = (float) $cartItem->price;
            $grandTotal = ($price * $cartItem->quantity) + $tax;

            $orderItems[] = [
                'product' => $buyable,
                'product_type' => $type,
                'product_id' => $buyable->id,
                'quantity' => $cartItem->quantity,
                'price' => $price,
                'configuration' => array_merge($configuration, [
                    'grand_total' => $grandTotal,
                ]),
            ];
        }

        return $orderItems;
    }
}
