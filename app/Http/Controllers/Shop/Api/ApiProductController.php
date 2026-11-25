<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;
class ApiProductController extends Controller
{
    protected $productService = null;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    //http://localhost/vaniloapp/public/api/filter?categories=mobile,electronics&min_price=100&max_price=50000&colors=black
    public function filter(Request $request)
    {
        $products = $this->productService->filter($request);
        $this->productService->transformProductForImage($products);
        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'products' => $products
        ]);
    }

    public function getProductBySlug($slug)
    {

    }

    public function products()
    {
        $products = MasterProduct::with([
            'media',
            'propertyValues',
            'variants',
            'taxons',
            'taxRate',
            'discount',
            // 'isInWishlist',
            // 'isincart',
        ])->get();

        $this->productService->transformProductForImage($products);

        return response()->json([
            'count' => $products->count(),
            'products' => $products
        ]);
    }


    public function getProducts(Request $request)
    {
        $query = MasterProduct::with(['propertyValues', 'variants', 'taxons', 'taxRate', 'discount', 'wishlist', 'isincart']);
        $this->filters($query, $request);
        $products = $query->paginate($request->integer('per_page', 20))->withQueryString();
        $this->productService->transformProductForImage($products->getCollection());
        return response()->json(['success' => true, 'count' => $products->total(), 'data' => $products]);
    }


    public function find_by_category($slug)
    {
        return $this->productService->getProductsByCategorySlug($slug);
    }

    public function getProductCategoryByProductSlug($slug)
    {
        return $this->productService->getProductCategoryByProductSlug($slug);
    }

    public function related(string $slug)
    {
        $product = MasterProduct::where('slug', $slug)->first();
        if (!$product)
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        try {
            $related = $this->productService->getRelatedProducts($product);
        } catch (\Throwable) {
            $related = collect();
        }
        $related->load(['media', 'taxons']);
        return response()->json(['success' => true, 'data' => $related->map(fn($p) => $this->transform($p))->values()]);
    }

    private function transform($product): array
    {
        $image = $product->getFirstMediaUrl('images', 'medium') ?: $product->getFirstMediaUrl('thumbnail_images', 'medium');
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => (float) $product->price,
            'stock' => (int) $product->stock,
            'state' => $product->state,
            'description' => $product->description,
            'image_url' => $image ?: null,
            'images' => $product->getMedia('images')->map(fn($m) => $m->getUrl('medium'))->values(),
            'categories' => $product->taxons->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug])->values(),
            'variants' => $product->variants->map(fn($v) => ['id' => $v->id, 'name' => $v->name, 'sku' => $v->sku, 'price' => (float) $v->price, 'stock' => (int) $v->stock, 'color' => $v->color, 'size' => $v->size])->values(),
            'attributes' => $product->attributes,
        ];
    }

    private function filters($query, Request $request): void
    {
        $csv = fn($v) => array_values(array_filter(array_map('trim', is_array($v) ? $v : ($v ? explode(',', $v) : []))));
        $categories = $csv($request->input('categories'));
        $colors = $csv($request->input('colors'));
        $sizes = $csv($request->input('sizes'));
        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = trim($request->search);
            $q->where(fn($s) => $s->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%"));
        });
        $query->when($categories, fn($q) => $q->whereHas('taxons', fn($t) => $t->whereIn('slug', $categories)));
        $query->when($request->filled('min_price'), fn($q) => $q->where('price', '>=', (float) $request->min_price));
        $query->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', (float) $request->max_price));
        $query->when($colors, fn($q) => $q->whereHas('variants', fn($v) => $v->whereIn('color', $colors)));
        $query->when($sizes, fn($q) => $q->whereHas('variants', fn($v) => $v->whereIn('size', $sizes)));
        match ($request->input('sort')) { 'price_low_high' => $query->orderBy('price'), 'price_high_low' => $query->orderByDesc('price'), 'a_to_z' => $query->orderBy('name'), 'z_to_a' => $query->orderByDesc('name'), default => $query->latest()};
    }

}
