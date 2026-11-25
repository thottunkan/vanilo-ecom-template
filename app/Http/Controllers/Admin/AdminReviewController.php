<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Review());
    }

    public function index()
    {
        $reviews = Review::with(['user', 'product'])->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function edit($id)
    {

    }

    public function delete($id)
    {

    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:master_products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            // 'status' => 'required|in:pending,approved,rejected',
        ]);
        $data['status'] = 'pending';
        Review::create($data);
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Review created successfully!'
            ], 200);
        }
        return view('admin.reviews.index');

    }

    public function update(Request $request, $id)
    {

    }

}
