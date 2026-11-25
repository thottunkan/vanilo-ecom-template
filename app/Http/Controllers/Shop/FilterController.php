<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Vanilo\Foundation\Models\MasterProduct;

class FilterController extends Controller
{

    protected ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = MasterProduct::with([
            'media',
            'variants',
            'propertyValues',
            'taxons'
        ])->get();
      //  $this->productService->transformProductForImage($products);
        return view('shop.filter', compact('products'));
    }

    //GET /products/filter?
    // categories=shirts,jeans
    // &colors=red,black
    // &sizes=M,L
    // &min_price=100
    // &max_price=1000
    // &featured=1
    // &sort=price_low_high
    public function filterProducts(Request $request)
    {
        $categories = $request->filled('categories')
            ? array_map('trim', explode(',', $request->categories))
            : [];

        $colors = $request->filled('colors')
            ? array_map('trim', explode(',', $request->colors))
            : [];

        $sizes = $request->filled('sizes')
            ? array_map('trim', explode(',', $request->sizes))
            : [];

        $products = MasterProduct::query()

            // Category filter
            ->when(!empty($categories), function ($query) use ($categories) {
                $query->whereHas('taxons', function ($q) use ($categories) {
                    $q->whereIn('slug', $categories);
                });
            })

            // Minimum price
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })

            // Maximum price
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })

            // Color filter
            ->when(!empty($colors), function ($query) use ($colors) {
                $query->whereIn('color', $colors);
            })

            // Size filter
            ->when(!empty($sizes), function ($query) use ($sizes) {
                $query->whereIn('size', $sizes);
            })

            // Featured products
            ->when($request->filled('featured') && $request->featured == 1, function ($query) {
                $query->where('is_featured', true);
            })

            // Sorting
            ->when($request->filled('sort'), function ($query) use ($request) {

                switch ($request->sort) {

                    case 'price_low_high':
                        $query->orderBy('price', 'asc');
                        break;

                    case 'price_high_low':
                        $query->orderBy('price', 'desc');
                        break;

                    case 'a_to_z':
                        $query->orderBy('name', 'asc');
                        break;

                    case 'z_to_a':
                        $query->orderBy('name', 'desc');
                        break;

                    case 'featured':
                        $query->where('is_featured', true);
                        break;
                }

            })

            ->with([
                'media',
                'taxons',
                'propertyValues',

                'variants' => function ($variantQuery) use ($request, $colors, $sizes) {

                    if ($request->filled('min_price')) {
                        $variantQuery->where('price', '>=', $request->min_price);
                    }

                    if ($request->filled('max_price')) {
                        $variantQuery->where('price', '<=', $request->max_price);
                    }

                    if (!empty($colors)) {
                        $variantQuery->whereIn('color', $colors);
                    }

                    if (!empty($sizes)) {
                        $variantQuery->whereIn('size', $sizes);
                    }

                }

            ])

            ->get();


        return view('shop.filter', compact('products'));
    }
}
