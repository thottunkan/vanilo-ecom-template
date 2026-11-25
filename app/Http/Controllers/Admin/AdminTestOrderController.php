<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\MasterProduct;

class AdminTestOrderController extends Controller
{
    public function index()
    {
    $orders = Order::with([
        'items.product',
    ])->get();
       
        return view('admin.orders.index', compact('orders'));
    }

    public function products(Request $request)
    {
        $products = MasterProduct::with([
            'variants',
            'propertyValues',
            'taxons'
        ])

            // Search title/content/name fields
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })

            // Status filter
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status);
            })

            ->when($request->filled('stock'), function ($query) use ($request) {
                $query->where('stock', $request->stock);
            })

            // Created date exact filter
            ->when($request->filled('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })

            // Updated date exact filter
            ->when($request->filled('updated_at'), function ($query) use ($request) {
                $query->whereDate('updated_at', $request->updated_at);
            })

            // Date range filter
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })

            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });


        // Sorting
        switch ($request->get('sort')) {

            case 'created_at_asc':
                $products->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $products->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $products->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }


        // Get all records (no pagination)
        $products = $products->get();


        return view('admin.orders.product.index', compact('products'));
    }

    public function create(Request $request)
    {

    }
}
