<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiReviewController extends Controller
{
    /**
     * Store a new review.
     */
    public function addReview(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:master_products,id',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = auth()->id();

        // Check if this user already reviewed this product
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Review already submitted for this product.',
                'data' => $existingReview,
            ], 409);
        }

        $data['user_id'] = $userId;
        $data['comments'] = $data['comment'];
        unset($data['comment']);
        $review = Review::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Review added successfully.',
            'data' => $review,
        ], 201);
    }

    /**
     * Delete a review.
     */
    public function deleteReview($id): JsonResponse
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found.',
                ], 404);
            }

            $review->delete();

            return response()->json([
                'status' => true,
                'message' => 'Review deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to delete review.',
            ]);
        }

    }

    /**
     * Get reviews by user ID.
     */
    public function getReviewsByUser()
    {
        try {
            $userId = auth()->id();
            $reviews = Review::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'status' => true,
                'message' => "Reviews fetched successfully..!",
                'data' => $reviews,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                "message" => "Unable to fetch reviews..!",
                'data' => $reviews,
            ]);
        }

    }

    /**
     * Get all reviews.
     */
    public function getReviews(): JsonResponse
    {
        $reviews = Review::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    public function updateStatus($id)
    {

    }
}