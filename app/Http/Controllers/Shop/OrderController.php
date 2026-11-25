<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Utils\ProductUtility;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Vanilo\Order\Factories\OrderFactory;
use Vanilo\MasterProduct\Models\MasterProduct;
use Throwable;
use Vanilo\Order\Models\Order;

class OrderController extends Controller
{


    // public function index()
    // {
    //     $pendingOrders = Order::with('items')
    //         ->ofUser(auth()->id())
    //         ->where('status', 'pending')
    //         ->get();
    //     $cancelledOrders = Order::with('items')
    //         ->ofUser(auth()->id())
    //         ->where('status', 'candelled')
    //         ->get();
    //     $deliveredOrders = Order::with('items')
    //         ->ofUser(auth()->id())
    //         ->where('status', 'delivered')
    //         ->get();

    //     return view('shop.orders.index', compact(
    //         'pendingOrders',
    //         'cancelledOrders',
    //         'deliveredOrders'
    //     ));
    // }

    public function index()
    {
        $statuses = [
            'pendingOrders' => 'pending',
            'cancelledOrders' => 'cancelled', // note: DB may have 'candelled' typo, see below
            'deliveredOrders' => 'delivered',
        ];

        $orders = [];

        foreach ($statuses as $key => $status) {
            $orders[$key] = Order::with('items')
                ->ofUser(auth()->id())
                ->where('status', $status)
                ->get()
                ->each(function ($order) {
                    $taxTotal = 0;
                    $grandTotal = 0;

                    foreach ($order->items as $item) {
                        $config = is_array($item->configuration)
                            ? $item->configuration
                            : json_decode($item->configuration ?? '{}', true);

                        $taxTotal += (float) data_get($config, 'tax', 0);
                        $grandTotal += (float) data_get($config, 'grand_total', 0);
                    }

                    $order->tax_total = round($taxTotal, 2);
                    $order->grand_total = round($grandTotal, 2);
                });
        }

        return view('shop.orders.index', compact('orders'))->with($orders);
    }


    public function store(Request $request, OrderFactory $factory)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.master_product_id' => 'required|integer',
            'items.*.master_product_variant_id' => 'nullable|integer',
            'items.*.configuration' => 'nullable|array',
            'items.*.configuration.color' => 'nullable|string',
            'items.*.configuration.size' => 'nullable|string',
            'items.*.configuration.tax' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $orderItems = [];

        foreach ($request->input('items') as $item) {
            $buyable = ProductUtility::has_product_varients(
                $item['master_product_id'],
                $item['master_product_variant_id'] ?? null
            );

            if (!$buyable) {
                return response()->json([
                    'status' => false,
                    'message' => "Product not found for master_product_id {$item['master_product_id']}.",
                ], 404);
            }

            $type = get_class($buyable) === MasterProduct::class
                ? 'master_product'
                : 'master_product_varient';

            $configuration = $item['configuration'] ?? [];
            $tax = (float) ($configuration['tax'] ?? 0);
            $price = (float) $buyable->price;
            $grandTotal = $price + $tax;

            $orderItems[] = [
                'product' => $buyable,
                'product_type' => $type,
                'product_id' => $buyable->id,
                'price' => $price,
                'configuration' => array_merge($configuration, [
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                ]),
            ];
        }

        try {
            $order = $factory->createFromDataArray([
                'user_id' => auth()->id(),
            ], ...$orderItems);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to place order.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully..!',
            'order' => $order,
        ], 201);
    }
}