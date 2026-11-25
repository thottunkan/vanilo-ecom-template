<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;

class ApiShopController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request, ?string $category = null)
    {
        $query = MasterProduct::query()
            ->with(['media', 'taxons', 'variants', 'discount'])
            ->where('state', 'active');

        $this->applyFilters($query, $request, $category);

        $products = $query->latest()->paginate($request->integer('per_page', 6))->withQueryString();
        $this->productService->transformProductForImage($products);
        $categories = Taxon::query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        foreach ($categories as $taxon) {
            $categoryIds = $this->getCategoryIds($taxon);

            $taxon->product_count = MasterProduct::query()
                ->where('state', 'active')
                ->whereHas('taxons', function ($query) use ($categoryIds) {
                    $query->whereIn('taxons.id', $categoryIds);
                })
                ->count();

            foreach ($taxon->children as $child) {
                $childCategoryIds = $this->getCategoryIds($child);

                $child->product_count = MasterProduct::query()
                    ->where('state', 'active')
                    ->whereHas('taxons', function ($query) use ($childCategoryIds) {
                        $query->whereIn('taxons.id', $childCategoryIds);
                    })
                    ->count();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products->items(),
                'categories' => $categories,
                'brands' => Brand::orderBy('name')->get(),
                'filters' => $request->only([
                    'search',
                    'categories',
                    'min_price',
                    'max_price',
                    'colors',
                    'sizes',
                    'sort',
                ]),
            ],
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $product = MasterProduct::with(['media', 'taxons', 'variants', 'propertyValues', 'discount'])
            ->where('slug', $slug)
            ->where('state', 'active')
            ->get();
        //$this->productService->transformProductForImage($product);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }
        $this->productService->transformProductForImage($product);
        
        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'related' => $this->productService->getRelatedProducts($product),
            ],
        ]);
    }

    public function search(Request $request)
    {
        $request->merge(['search' => $request->input('q', $request->input('search'))]);

        return $this->index($request);
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
                $t->whereIn('taxons.id', $taxonIds);
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

    private function getDescendantIds(Taxon $taxon): array
    {
        $ids = [];

        foreach ($taxon->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
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
            $ids = array_merge($ids, $this->getCategoryIds($child));
        }

        return $ids;
    }
}