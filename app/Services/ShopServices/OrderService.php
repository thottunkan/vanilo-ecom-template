<?php

namespace App\Services\ShopServices;
use App\Utils\ProductUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanilo\Foundation\Models\Order;
use Vanilo\Order\Contracts\OrderFactory;

class OrderService
{

    protected OrderFactory $orderFactory;

    public function __construct(OrderFactory $orderFactory)
    {
        $this->orderFactory = $orderFactory;
    }

    public function getOrders()
    {

        try {
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
            return response()->json(['status' => true, 'orders' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'orders' => []]);
        }



    }


    //     {
    //     "shipping_address": "123 Sheikh Zayed Road, Dubai, UAE",
    //     "billing_address": "123 Sheikh Zayed Road, Dubai, UAE",
    //     "items": [
    //         {
    //             "master_product_id": 1,
    //             "master_product_variant_id": 2,
    //             "quantity": 2,
    //             "configuration": {
    //                 "color": "Black",
    //                 "size": "L",
    //                 "tax": 18
    //             }
    //         },
    //         {
    //             "master_product_id": 3,
    //             "master_product_variant_id": null,
    //             "quantity": 1,
    //             "configuration": {
    //                 "color": "White",
    //                 "size": "M",
    //                 "tax": 10
    //             }
    //         }
    //     ]
    // }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => [
                'nullable',
                'string',
            ],

            'billing_address' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.master_product_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.master_product_variant_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'items.*.quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'items.*.configuration' => [
                'nullable',
                'array',
            ],

            'items.*.configuration.color' => [
                'nullable',
                'string',
            ],

            'items.*.configuration.size' => [
                'nullable',
                'string',
            ],

            'items.*.configuration.tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        try {
            $grandTotalForOrders = 0;

            $order = DB::transaction(function () use ($validated, &$grandTotalForOrders) {
                $orderItems = [];

                foreach ($validated['items'] as $item) {

                    $masterProductId = (int) $item['master_product_id'];

                    $variantId = isset($item['master_product_variant_id'])
                        ? (int) $item['master_product_variant_id']
                        : null;

                    /*
                     * Get the actual Vanilo buyable.
                     *
                     * If variant ID is supplied:
                     *     returns the variant.
                     *
                     * If variant ID is null:
                     *     returns the master product.
                     */
                    $buyable = ProductUtility::has_product_varients(
                        $masterProductId,
                        $variantId
                    );

                    if (!$buyable) {
                        throw new \Exception(
                            "Product not found for master_product_id {$masterProductId}."
                        );
                    }

                    /*
                     * Determine Vanilo product type.
                     */
                    $class = get_class($buyable);

                    if ($class === 'Vanilo\MasterProduct\Models\MasterProduct') {
                        $type = 'master_product';
                    } else {
                        $type = 'master_product_varient';
                    }

                    /*
                     * Quantity
                     */
                    $quantity = (int) ($item['quantity'] ?? 1);

                    /*
                     * Configuration
                     */
                    $configuration = $item['configuration'] ?? [];

                    $tax = (float) ($configuration['tax'] ?? 0);

                    /*
                     * Always get the price from the database.
                     */
                    $price = (float) $buyable->price;

                    /*
                     * Calculate the item grand total.
                     */
                    $grandTotal = ($price * $quantity) + $tax;

                    /*
                     * Add calculated values to configuration.
                     */
                    $configuration['tax'] = $tax;
                    $configuration['grand_total'] = $grandTotal;

                    /*
                     * Add item to order items.
                     */
                    $orderItems[] = [
                        'product' => $buyable,
                        'product_type' => $type,
                        'product_id' => $buyable->id,
                        'price' => $price,
                        'quantity' => $quantity,
                        'configuration' => $configuration,
                    ];

                    /*
                     * Calculate total for all order items.
                     */
                    $grandTotalForOrders += $grandTotal;
                }

                /*
                 * Create the order using the same structure
                 * as your working place_order() method.
                 */
                return $this->orderFactory->createFromDataArray(
                    [
                        'user_id' => auth()->id(),
                        'shipping_address' => $validated['shipping_address'] ?? null,
                        'billing_address' => $validated['billing_address'] ?? null,
                    ],
                    $orderItems
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'data' => [
                    'order' => $order,
                    'grand_total' => $grandTotalForOrders,
                ],
            ], 201);

        } catch (\Exception $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : 'Something went wrong while placing the order.',
            ], 500);
        }
    }

    public function orders()
    {
        $statuses = [
            'pendingOrders' => 'pending',
            'cancelledOrders' => 'cancelled',
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

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ], 200);
    }



    public function getOrdersByStatus(string $status)
    {
        $orders = Order::with('items')
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

        return response()->json([
            'success' => true,
            'message' => ucfirst($status) . ' orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

}