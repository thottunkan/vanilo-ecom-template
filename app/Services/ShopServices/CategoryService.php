<?php
namespace App\Services\ShopServices;

use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;
class CategoryService
{

    public function getCategories()
    {
        $categories = Taxon::withCount('products')->get();
        return response()->json([
            'status' => true,
            'categories' => $categories
        ], 200);
    }

    public function getProductsByCategorySlug($slug)
    {
        $category = Taxon::where('slug', $slug)->firstOrFail();

        $products = MasterProduct::with([
            'variants',
            'taxons'
        ])
            ->whereHas('taxons', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            ->get();

        $this->transformProductForImage($products);

        return response()->json([
            'category' => $category,
            'product_count' => $products->count(),
            'products' => $products,
        ], 200);
    }

    public function getProductCategoryByProductSlug($slug)
    {
        $product = MasterProduct::where('slug', $slug)->with('taxons')->get();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'taxons' => $product->taxons
        ], 200);
    }

    public function transformProductForImage($products)
    {
        return $products->transform(function ($product) {

            $product->images = $product->getMedia('images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'thumbnail_url' => $media->getUrl('thumb'),
                    'original_url' => asset($media->getUrl('medium')),
                    'is_primary' => $media->getCustomProperty('isPrimary', false),
                ];
            });

            $product->thumbnail_images = $product->getMedia('thumbnail_images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'thumbnail_url' => $media->getUrl('thumb'),
                    'original_url' => $media->getUrl('medium'),
                    'is_primary' => $media->getCustomProperty('isPrimary', false),
                ];
            });

            return $product;
        });
    }
}