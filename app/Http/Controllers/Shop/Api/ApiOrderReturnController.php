<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;

use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;
use Vanilo\Foundation\Models\Order;

class ApiOrderReturnController extends Controller
{
    /**
     * Create a return request.
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

            /*
             * Validate request.
             */
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

                'items.*.condition' => [
                    'nullable',
                    'string',
                    'max:50',
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
                    'message' => 'You are not authorized to return this order.',
                ], 403);
            }

            /*
             * Return is allowed only after delivery/completion.
             */
            if (!in_array($order->status, [
                'delivered',
                'completed',
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order is not eligible for return.',
                ], 422);
            }

            /*
             * Check if an active return already exists.
             */
            $existingReturn = OrderReturn::where('order_id', $order->id)
                ->whereIn('status', [
                    'requested',
                    'approved',
                    'received',
                ])
                ->exists();

            if ($existingReturn) {
                return response()->json([
                    'success' => false,
                    'message' => 'A return request already exists for this order.',
                ], 422);
            }

            /*
             * Create return and return items.
             */
            $return = DB::transaction(function () use (
                $validated,
                $order
            ) {

                $returnNumber = $this->generateReturnNumber();

                $orderReturn = OrderReturn::create([
                    'order_id' => $order->id,
                    'return_number' => $returnNumber,
                    'status' => 'requested',
                    'reason' => $validated['reason'],
                    'customer_note' => $validated['customer_note'] ?? null,
                    'refund_amount' => 0,
                    'requested_at' => now(),
                ]);

                $totalRefund = 0;

                foreach ($validated['items'] as $item) {

                    /*
                     * Make sure item belongs to this order.
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
                     * Calculate already returned quantity.
                     */
                    $alreadyReturned = OrderReturnItem::where(
                        'order_item_id',
                        $orderItem->id
                    )
                        ->whereHas('orderReturn', function ($query) {
                            $query->whereNotIn('status', [
                                'rejected',
                            ]);
                        })
                        ->sum('quantity');

                    /*
                     * Calculate remaining quantity.
                     */
                    $remainingQuantity =
                        (int) $orderItem->quantity -
                        (int) $alreadyReturned;

                    /*
                     * Prevent over-return.
                     */
                    if ($item['quantity'] > $remainingQuantity) {

                        throw new \Exception(
                            "Return quantity for order item {$orderItem->id} " .
                            "cannot exceed {$remainingQuantity}."
                        );
                    }

                    /*
                     * Product price.
                     */
                    $itemPrice = (float) $orderItem->price;

                    /*
                     * Refund amount.
                     */
                    $refundAmount =
                        $itemPrice * (int) $item['quantity'];

                    $totalRefund += $refundAmount;

                    /*
                     * Create return item.
                     */
                    OrderReturnItem::create([
                        'order_return_id' => $orderReturn->id,
                        'order_item_id' => $orderItem->id,
                        'quantity' => $item['quantity'],
                        'reason' => $item['reason']
                            ?? $validated['reason'],
                        'condition' => $item['condition'] ?? null,
                        'refund_amount' => $refundAmount,
                    ]);
                }

                /*
                 * Update total refund.
                 */
                $orderReturn->update([
                    'refund_amount' => $totalRefund,
                ]);

                return $orderReturn->load([
                    'order',
                    'orderReturnItems.orderItem',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Return request created successfully.',
                'data' => $return,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create return request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get all returns for an order.
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

            $returns = OrderReturn::where(
                'order_id',
                $order->id
            )
                ->with([
                    'orderReturnItems.orderItem',
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Returns retrieved successfully.',
                'data' => $returns,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve returns.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get a single return.
     */
    public function show(Request $request, $returnId): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $return = OrderReturn::with([
                'order',
                'orderReturnItems.orderItem',
            ])->find($returnId);

            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return request not found.',
                ], 404);
            }

            /*
             * Check ownership through order.
             */
            if (
                !$return->order ||
                (int) $return->order->user_id !== (int) $user->id
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this return.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Return retrieved successfully.',
                'data' => $return,
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve return.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update return status.
     *
     * requested
     * approved
     * rejected
     * received
     * refunded
     * completed
     */
    public function updateStatus(
        Request $request,
        $returnId
    ): JsonResponse {

        try {

            $validated = $request->validate([
                'status' => [
                    'required',
                    'in:requested,approved,rejected,received,refunded,completed',
                ],

                'admin_comment' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);

            $return = OrderReturn::find($returnId);

            if (!$return) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return request not found.',
                ], 404);
            }

            DB::transaction(function () use (
                $return,
                $validated
            ) {

                $status = $validated['status'];

                $updateData = [
                    'status' => $status,
                ];

                /*
                 * Status timestamps.
                 */
                if ($status === 'approved') {
                    $updateData['approved_at'] = now();
                }

                if ($status === 'received') {
                    $updateData['received_at'] = now();
                }

                if ($status === 'refunded') {
                    $updateData['refunded_at'] = now();
                }

                $return->update($updateData);
            });

            $return->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Return status updated successfully.',
                'data' => [
                    'return_id' => $return->id,
                    'return_number' => $return->return_number,
                    'status' => $return->status,
                    'refund_amount' => $return->refund_amount,
                    'requested_at' => $return->requested_at,
                    'approved_at' => $return->approved_at,
                    'received_at' => $return->received_at,
                    'refunded_at' => $return->refunded_at,
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update return status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Generate unique return number.
     */
    private function generateReturnNumber(): string
    {
        do {

            $number = 'RET-' .
                strtoupper(Str::random(10));

        } while (
            OrderReturn::where(
                'return_number',
                $number
            )->exists()
        );

        return $number;
    }
}