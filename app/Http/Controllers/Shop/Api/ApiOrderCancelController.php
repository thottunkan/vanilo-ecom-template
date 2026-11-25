<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderCancel;
use App\Models\OrderCancelItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vanilo\Order\Models\Order;

class ApiOrderCancelController extends Controller
{
    /**
     * Create a cancellation request.
     */
    public function store(Request $request, $orderId): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $validated = $request->validate([
                'reason' => [
                    'required',
                    'string',
                    'max:1000',
                ],

                'customer_note' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.order_item_id' => [
                    'required',
                    'integer',
                ],

                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'items.*.reason' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ]);

            /*
             * Find order.
             */
            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            /*
             * Check order ownership.
             */
            if ((int) $order->user_id !== (int) $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this order.',
                ], 403);
            }

            /*
             * Cancellation is only allowed before shipment.
             */
            if (!in_array($order->status, [
                'pending',
                'confirmed',
                'processing',
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order is not eligible for cancellation.',
                ], 422);
            }

            /*
             * Check existing active cancellation.
             */
            $existingCancel = OrderCancel::where('order_id', $order->id)
                ->whereIn('status', [
                    'requested',
                    'approved',
                ])
                ->exists();

            if ($existingCancel) {
                return response()->json([
                    'success' => false,
                    'message' => 'A cancellation request already exists for this order.',
                ], 422);
            }

            /*
             * Create cancellation.
             */
            $cancel = DB::transaction(function () use (
                $validated,
                $order
            ) {

                $cancelNumber = $this->generateCancelNumber();

                $orderCancel = OrderCancel::create([
                    'order_id' => $order->id,
                    'cancel_number' => $cancelNumber,
                    'status' => 'requested',
                    'reason' => $validated['reason'],
                    'customer_note' => $validated['customer_note'] ?? null,
                    'refund_amount' => 0,
                    'requested_at' => now(),
                ]);

                $totalRefund = 0;

                foreach ($validated['items'] as $item) {

                    /*
                     * Make sure the item belongs to this order.
                     */
                    $orderItem = $order->items()
                        ->where('id', $item['order_item_id'])
                        ->first();

                    if (!$orderItem) {
                        throw new \Exception(
                            'Invalid order item: ' .
                            $item['order_item_id']
                        );
                    }

                    /*
                     * Calculate quantity already cancelled.
                     */
                    $alreadyCancelled = OrderCancelItem::where(
                        'order_item_id',
                        $orderItem->id
                    )
                        ->whereHas('orderCancel', function ($query) {
                            $query->whereNotIn('status', [
                                'rejected',
                            ]);
                        })
                        ->sum('quantity');

                    /*
                     * Remaining cancellable quantity.
                     */
                    $remainingQuantity =
                        (int) $orderItem->quantity -
                        (int) $alreadyCancelled;

                    if ($remainingQuantity <= 0) {
                        throw new \Exception(
                            "Order item {$orderItem->id} has already been fully cancelled."
                        );
                    }

                    /*
                     * Prevent cancellation beyond available quantity.
                     */
                    if ($item['quantity'] > $remainingQuantity) {
                        throw new \Exception(
                            "Cancellation quantity for order item " .
                            "{$orderItem->id} cannot exceed " .
                            "{$remainingQuantity}."
                        );
                    }

                    /*
                     * Calculate refund.
                     */
                    $itemPrice = (float) $orderItem->price;

                    $refundAmount =
                        $itemPrice * (int) $item['quantity'];

                    $totalRefund += $refundAmount;

                    /*
                     * Create cancellation item.
                     */
                    OrderCancelItem::create([
                        'order_cancel_id' => $orderCancel->id,
                        'order_item_id' => $orderItem->id,
                        'quantity' => $item['quantity'],
                        'reason' => $item['reason']
                            ?? $validated['reason'],
                        'refund_amount' => $refundAmount,
                    ]);
                }

                /*
                 * Update total refund.
                 */
                $orderCancel->update([
                    'refund_amount' => $totalRefund,
                ]);

                return $orderCancel->load([
                    'order',
                    'orderCancelItems.orderItem',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Cancellation request created successfully.',
                'data' => $cancel,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create cancellation request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get all cancellations for an order.
     */
    public function index(Request $request, $orderId): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            if ((int) $order->user_id !== (int) $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this order.',
                ], 403);
            }

            $cancels = OrderCancel::where(
                'order_id',
                $order->id
            )
                ->with([
                    'orderCancelItems.orderItem',
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Cancellation requests retrieved successfully.',
                'data' => $cancels,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cancellation requests.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get a single cancellation.
     */
    public function show(
        Request $request,
        $cancelId
    ): JsonResponse {

        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $cancel = OrderCancel::with([
                'order',
                'orderCancelItems.orderItem',
            ])->find($cancelId);

            if (!$cancel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cancellation request not found.',
                ], 404);
            }

            /*
             * Check ownership.
             */
            if (
                !$cancel->order ||
                (int) $cancel->order->user_id !== (int) $user->id
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this cancellation.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cancellation request retrieved successfully.',
                'data' => $cancel,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cancellation request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update cancellation status.
     *
     * requested
     * approved
     * rejected
     * completed
     */
    public function updateStatus(
        Request $request,
        $cancelId
    ): JsonResponse {

        try {

            $validated = $request->validate([
                'status' => [
                    'required',
                    'in:requested,approved,rejected,completed',
                ],

                'admin_comment' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);

            $cancel = OrderCancel::with([
                'order',
                'orderCancelItems',
            ])->find($cancelId);

            if (!$cancel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cancellation request not found.',
                ], 404);
            }

            DB::transaction(function () use (
                $cancel,
                $validated
            ) {

                $status = $validated['status'];

                $updateData = [
                    'status' => $status,
                ];

                /*
                 * Save admin comment.
                 */
                if (!empty($validated['admin_comment'])) {
                    $updateData['admin_comment'] =
                        $validated['admin_comment'];
                }

                /*
                 * Status timestamps.
                 */
                if ($status === 'approved') {
                    $updateData['approved_at'] = now();
                }

                if ($status === 'rejected') {
                    $updateData['rejected_at'] = now();
                }

                if ($status === 'completed') {
                    $updateData['completed_at'] = now();
                }

                $cancel->update($updateData);

                /*
                 * If cancellation is completed,
                 * check whether the entire order is cancelled.
                 */
                if (
                    $status === 'completed' &&
                    $cancel->order
                ) {

                    $order = $cancel->order;

                    $allCancelled = true;

                    foreach ($order->items as $orderItem) {

                        $cancelledQuantity =
                            OrderCancelItem::where(
                                'order_item_id',
                                $orderItem->id
                            )
                            ->whereHas(
                                'orderCancel',
                                function ($query) {
                                    $query->where(
                                        'status',
                                        'completed'
                                    );
                                }
                            )
                            ->sum('quantity');

                        if (
                            $cancelledQuantity <
                            $orderItem->quantity
                        ) {
                            $allCancelled = false;
                            break;
                        }
                    }

                    /*
                     * Only mark order cancelled when
                     * every item has been cancelled.
                     */
                    if ($allCancelled) {

                        $order->update([
                            'status' => 'cancelled',
                        ]);
                    }
                }
            });

            $cancel->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Cancellation status updated successfully.',
                'data' => [
                    'cancel_id' => $cancel->id,
                    'cancel_number' => $cancel->cancel_number,
                    'status' => $cancel->status,
                    'refund_amount' => $cancel->refund_amount,
                    'requested_at' => $cancel->requested_at,
                    'approved_at' => $cancel->approved_at,
                    'rejected_at' => $cancel->rejected_at,
                    'completed_at' => $cancel->completed_at,
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update cancellation status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Generate unique cancellation number.
     */
    private function generateCancelNumber(): string
    {
        do {

            $number = 'CNL-' .
                strtoupper(Str::random(10));

        } while (
            OrderCancel::where(
                'cancel_number',
                $number
            )->exists()
        );

        return $number;
    }
}