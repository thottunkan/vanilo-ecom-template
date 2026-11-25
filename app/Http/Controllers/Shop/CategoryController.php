<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\Product;

class CategoryController extends Controller
{

    ///category/electronics?color[]=Red&color[]=Blue&size[]=XL&size[]=XXL
    public function getProductCountByCategory(Request $request, $slug)
    {
        $category = Taxon::with('children')
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $this->getCategoryIds($category);

        $products = Product::query()
            ->whereHas('taxons', function ($query) use ($categoryIds) {
                $query->whereIn('taxons.id', $categoryIds);
            })->when($request->filled('search'), function ($query) use ($request) {
                $keywords = preg_split('/[\s,+]+/', trim($request->search));
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->where(function ($subQuery) use ($keyword) {
                            $subQuery->where('name', 'LIKE', "%{$keyword}%")
                                ->orWhere('slug', 'LIKE', "%{$keyword}%")
                                ->orWhere('sku', 'LIKE', "%{$keyword}%")
                                ->orWhere('description', 'LIKE', "%{$keyword}%");
                        });
                    }
                });
            })

            // Min Price
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })

            // Max Price
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })

            // Color
            ->when($request->filled('colors'), function ($query) use ($request) {
                $query->whereHas('variants', function ($variant) use ($request) {
                    $variant->whereIn('colors', $request->color);
                });
            })

            // Size
            ->when($request->filled('sizes'), function ($query) use ($request) {
                $query->whereHas('variants', function ($variant) use ($request) {
                    $variant->whereIn('sizes', $request->size);
                });
            })

            ->paginate(6)
            ->withQueryString();

        return view('shop.category', compact(
            'category',
            'products'
        ));
    }

    private function getCategoryIds(Taxon $category): array
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge(
                $ids,
                $this->getCategoryIds($child)
            );
        }
        return $ids;
    }

    public function getCategories()
    {
        $categories = Taxon::withCount('products')->get();
        return response()->json([
            'category_count' => $categories->count(),
            'categories' => $categories,
        ]);
    }
}
