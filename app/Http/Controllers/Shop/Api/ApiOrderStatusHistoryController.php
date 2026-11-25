<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStatusHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vanilo\Order\Models\Order;

class ApiOrderStatusHistoryController extends Controller
{
    /**
     * Get order status history.
     */
    public function index(Request $request, $orderId)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $order = Order::with([
                'statusHistories' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])->find($orderId);
            //  return $order->status;
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            // Make sure the order belongs to the logged-in user
            if ((int) $order->user_id !== (int) $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this order.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status history retrieved successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->number ?? null,
                    'current_status' => $order->status,
                    'status_history' => $order->statusHistories,
                ],
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order status history.',
                'error' => $e->getMessage(),
            ], 500);
        }
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

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}