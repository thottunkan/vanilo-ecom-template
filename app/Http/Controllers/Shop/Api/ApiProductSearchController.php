<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;

class ApiProductSearchController extends Controller
{

    // GET /api/products
    // GET /api/products/shoes
    // GET /api/products?search=nike&colors=red,black&sizes=M,L
    // GET /api/products?min_price=50&max_price=200&sort=price_low_high
    // GET /api/products/shoes?search=running&sort=price_high_low&page=2

    public function search(Request $request, ?string $category = null)
    {
        $query = MasterProduct::query()
            ->with(['media', 'taxons', 'variants', 'discount'])
            ->where('state', 'active');

        $this->applyFilters($query, $request, $category);

        $products = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => [
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => [
                    'search' => $request->input('search'),
                    'categories' => $request->input('categories'),
                    'min_price' => $request->input('min_price'),
                    'max_price' => $request->input('max_price'),
                    'colors' => $request->input('colors'),
                    'sizes' => $request->input('sizes'),
                    'sort' => $request->input('sort'),
                ],
            ],
        ]);
    }

    private function applyFilters($query, Request $request, ?string $category = null): void
    {
        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = trim($request->input('search'));

            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });

        $categories = $this->csv($request->input('categories'));

        if (empty($categories) && $category) {
            $categories = [$category];
        }

        $colors = $this->csv($request->input('colors'));
        $sizes = $this->csv($request->input('sizes'));

        $query->when($categories, function ($q) use ($categories) {
            $taxonIds = $this->resolveTaxonIds($categories);

            $q->whereHas('taxons', function ($t) use ($taxonIds) {
                $t->whereIn('id', $taxonIds);
            });
        });

        $query->when(
            $request->filled('min_price'),
            fn($q) => $q->where('price', '>=', (float) $request->min_price)
        );

        $query->when(
            $request->filled('max_price'),
            fn($q) => $q->where('price', '<=', (float) $request->max_price)
        );

        $query->when($colors, function ($q) use ($colors) {
            $q->whereIn('color', $colors);
        });

        // $query->when($colors, function ($q) use ($colors) {
        //     $q->whereHas('variants', function ($v) use ($colors) {
        //         $v->whereIn('color', $colors);
        //     });
        // });

        $query->when($sizes, function ($q) use ($sizes) {
            $q->whereHas('variants', function ($v) use ($sizes) {
                $v->whereIn('size', $sizes);
            });
        });

        match ($request->input('sort')) {
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            'a_to_z' => $query->orderBy('name'),
            'z_to_a' => $query->orderByDesc('name'),
            default => null,
        };
    }
    private function getDescendantIds(Taxon $taxon): array
    {
        $ids = [];

        foreach ($taxon->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }

    private function resolveTaxonIds(array $slugs): array
    {
        $taxons = Taxon::whereIn('slug', $slugs)->with('children')->get();

        $selectedIds = $taxons->pluck('id')->all();

        $leafSelections = $taxons->filter(function ($taxon) use ($selectedIds) {
            return !in_array($taxon->parent_id, $selectedIds, true);
        });

        $ids = [];

        foreach ($leafSelections as $taxon) {
            $ids[] = $taxon->id;
            $ids = array_merge($ids, $this->getDescendantIds($taxon));
        }

        return array_values(array_unique($ids));
    }

    private function csv($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }

        return $value ? array_values(array_filter(array_map('trim', explode(',', $value)))) : [];
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

    public function update()
    {

    }

    public function delete()
    {

    }
}
